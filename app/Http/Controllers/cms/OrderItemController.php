<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderItemRequest;
use App\Http\Requests\UpdateOrderItemRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class OrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Cache::remember('orderItem_all', 200, function () {
            return OrderItem::with('product')->orderBy('created_at', 'desc')->get();
        });
        
        if ($request->ajax()) {
            $user = auth()->user();
            $userRoles = Cache::get("user_roles_{$user->id}", []); // Default to empty array if null
            $userPermissions = Cache::get("user_permissions_{$user->id}", []);

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    if(is_null($row->created_at)){
                        return 'N/A';
                    }
                    return date_format($row->created_at, 'Y/m/d H:i');
                })
                ->editColumn('fk_product', function ($row) {
                    if(is_null($row->fk_product)){
                        return 'N/A';
                    }
                    return $row->product->title;
                })
                ->addColumn('action', function ($row) use ($user, $userRoles, $userPermissions) {
                    $btn_edit = $btn_del = null;
                    if (in_array('superadmin', $userRoles) || in_array('admin', $userRoles) || in_array('editor', $userRoles) || $user->id == $row->fk_user) {
                        $btn_edit = '<a data-toggle="tooltip" 
                                        href="' . route('orderItems.edit', $row->id) . '" 
                                        class="btn btn-link btn-primary btn-lg" 
                                        data-original-title="Edit Record">
                                    <i class="fa fa-edit"></i>
                                </a>';
                    }

                    if (in_array('superadmin', $userRoles)) {
                        $btn_del = '<button type="button" 
                                    data-toggle="tooltip" 
                                    title="" 
                                    class="btn btn-link btn-danger" 
                                    onclick="delRecord(`' . $row->id . '`, `' . route('orderItems.destroy', $row->id) . '`, `#tb_orderItems`)"
                                    data-original-title="Remove">
                                <i class="fa fa-times"></i>
                            </button>';
                    }
                    return $btn_edit . $btn_del;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'fk_order' => 'required|exists:orders,id',
            'fk_product' => 'required|exists:products,id',
            'price' => 'required',
            'quantity' => 'required|min:1',
            'total_amount' => 'required',
        ]);
 
        if ($validator->fails()) {
            return redirect()->route('orders.show', ['order' => $request->fk_order])
                        ->with('errors', 'Some fields failed validation');
        }
        OrderItem::create([
            'fk_user' => auth()->user()->id,
            'fk_product' => $request->fk_product,
            'fk_order' => $request->fk_order,
            'quantity' => $request->quantity,
            'unit_price' => $request->price,
            'amount' => $request->total_amount,
        ]);

        // update order
        $order = Order::find($request->fk_order);
        $order->amount += (float) $request->total_amount;
        $order->status = 'pending';
        $order->save();

        // reduce stock
        $product = Product::find($request->fk_product);
        $product->quantity -= (int) $request->quantity;
        $product->save();

        return redirect()->route('orders.show', ['order' => $request->fk_order])
        ->with('success', 'Item Added Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderItem $orderItem)
    {
        return response()
            ->json([
                'orderItem' => $orderItem, 
            ], 200, ['JSON_PRETTY_PRINT' => JSON_PRETTY_PRINT]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderItem $orderItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderItem $orderItem)
    {
        DB::beginTransaction();

        $prev_order_quantity = $orderItem->quantity;
        if($request->filled('quantity')){
            $orderItem->quantity = $request->quantity;
        }

        if($request->filled('price')){
            $orderItem->unit_price = $request->price;
        }

        if($request->filled('total_amount')){
            $item_total_amount = $orderItem->amount;
            $orderItem->amount = $request->total_amount;
        }

        try {
            if($orderItem->save()){
                // update order
                $order = Order::find($request->fk_order);
                $total_amount = $order->amount;
                $order->amount = (float)(($total_amount -  $item_total_amount) + $request->total_amount);

                if($order->save()){
                    // reduce stock
                    $product = Product::find($orderItem->product->id);
                    // dd($product->quantity, $prev_order_quantity, $request->quantity);
                    $product->quantity = ( (int)$product->quantity + (int) $prev_order_quantity ) - (int) $request->quantity;
    
                    if($product->save()){
                        DB::commit();
                        return redirect()->route('orders.show', ['order' => $order->id])->with('success', 'Item in the Order successfully updated');
                    }
                }
    
            }
            return redirect()->back()->with('error', 'Error while updating your item in the Order');
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        

        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderItem $orderItem)
    {
        if ($orderItem->delete()) {
            $product = Product::find($orderItem->product->id);
            $product->quantity +=  $orderItem->quantity;
            $product->save();
            return response()->json([
                'code' => 1,
                'msg' => 'Record deleted successfully'
            ], 200, ['JSON_PRETTY_PRINT' => JSON_PRETTY_PRINT]);
        }
        
        return response()->json([
            'code' => -1,
            'msg' => 'Record did not delete'
        ], 422, ['JSON_PRETTY_PRINT' => JSON_PRETTY_PRINT]);
    }
}
