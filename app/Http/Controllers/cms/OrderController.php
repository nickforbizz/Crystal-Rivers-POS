<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\Customer;
use App\Models\OrderItem;
use App\Models\Product;
use DataTables;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Cache::remember('order_all', 60, function () {
            return Order::with('customer')->orderBy('created_at', 'desc')->get();
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
                ->editColumn('order_date', function ($row) {
                    if(is_null($row->order_date)){
                        return 'N/A';
                    }
                    return date_format($row->order_date, 'Y/m/d');
                })
                ->editColumn('customer', function ($row) {
                    if(is_null($row->fk_customer)){
                        return 'N/A';
                    }
                    return $row->customer?->email;
                })
                ->addColumn('action', function ($row) use ($user, $userRoles, $userPermissions) {
                    $btn_edit = $btn_del = null;
                    if (in_array('superadmin', $userRoles) || in_array('admin', $userRoles) || in_array('editor', $userRoles) || $user->id == $row->created_by) {
                        $btn_edit = '<a data-toggle="tooltip" 
                                        href="' . route('orders.edit', $row->id) . '" 
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
                                    onclick="delRecord(`' . $row->id . '`, `' . route('orders.destroy', $row->id) . '`, `#tb_orders`)"
                                    data-original-title="Remove">
                                <i class="fa fa-times"></i>
                            </button>';
                    }
                    return $btn_edit . $btn_del;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $customers = Cache::remember('customer_all', 200, function () {
            return Customer::select('id', 'names', 'email')->where('active',1)->get();
        });

        return view('cms.orders.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Cache::remember('customer_all', 200, function () {
            return Customer::where('active',1)->get();
        });

       
        return view('cms.orders.create', compact('customers', 'order'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request)
    {

        $order = Order::create($request->validated());
        if($order->save()){
            return redirect()->route('orders.show', ['order' => $order->id])->with('success', 'Order successfully initialized');
        }
        return redirect()->back()->with('error', 'Error while Initializing your Order');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order, Request $request)
    {
        $data = Cache::remember('order_item_all', 2, function () use ($order) {
            return OrderItem::with('product')->where('fk_order', $order->id)->orderBy('created_at', 'desc')->get();
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
                    if($row->order->status == 'completed'){
                        return 'N/A';
                    }
                    $btn_edit = $btn_del = null;
                    if (in_array('superadmin', $userRoles) || in_array('admin', $userRoles) || in_array('editor', $userRoles) || $user->id == $row->created_by) {
                        $btn_edit = '<button data-toggle="tooltip" 
                                        onclick="editOrderItem(`' . $row->id . '`, `' . route('order_items.show', $row->id) . '`, `' . route('order_items.update', $row->id) . '`)"
                                        class="btn btn-link btn-primary btn-lg" 
                                        data-original-title="Edit Record">
                                    <i class="fa fa-edit"></i>
                                    </button>';
                    }

                    if (in_array('superadmin', $userRoles)) {
                        $btn_del = '<button type="button" 
                                    data-toggle="tooltip" 
                                    title="" 
                                    class="btn btn-link btn-danger" 
                                    onclick="delRecord(`' . $row->id . '`, `' . route('order_items.destroy', $row->id) . '`, `#tb_order_items`)"
                                    data-original-title="Remove">
                                <i class="fa fa-times"></i>
                            </button>';
                    }
                    return $btn_edit . $btn_del;
                })
                ->rawColumns(['action'])
                ->make(true);
        }


        $products = Cache::remember('product_all', 60, function () {
            return Product::where('active',1)->get();
        });

        return view('cms.orders.items', compact('products', 'order'))->with('success', 'Order successfully initialized');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $customers = Cache::remember('customer_all', 200, function () {
            return Customer::where('active',1)->get();
        });
        return view('cms.orders.create', compact('order', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderRequest $request, Order $order)
    {
        $order->update($request->validated());

        // Redirect the user to the user's profile page
        return redirect()
            ->route('orders.index')
            ->with('success', 'Record updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        if ($order->delete()) {
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

    public function invoice(Order $order) {
        // Load the view file and pass order data to it
        $pdf = Pdf::loadView('cms.orders.invoice', compact('order'));

        // Render the PDF and force download
        return $pdf->download('invoice_' . $order->order_number . '.pdf');
    }
}
