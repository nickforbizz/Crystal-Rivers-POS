<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Http\Requests\TransactionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use DataTables;
use App\Models\Order;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Cache::remember('transaction_all', 10, function () {
            return Transaction::with('order')->with('user')->orderBy('created_at', 'desc')
                ->get();
        });
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('fk_user', function ($row) {
                    return $row->user->name;
                })
                ->editColumn('created_at', function ($row) {
                    return date_format($row->created_at, 'Y/m/d H:i');
                })
                ->editColumn('fk_order', function ($row) {
                    return '<a data-toggle="tooltip" 
                            href="' . route('orders.show', $row->order->id) . '" 
                            class="">
                        ' . $row->order->order_ID . '
                    </a>';
                })
                ->addColumn('action', function ($row) {
                    $btn_edit = $btn_del = null;

                    return $btn_edit . $btn_del;
                })
                ->rawColumns(['fk_order', 'action'])
                ->make(true);
        }

        return view('cms.transactions.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Order $order)
    {
        return view('transactions.create', compact('order'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Order $order)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,mpesa',
        ]);

        if ($order->amount < 1) {
            return redirect()->route('orders.show', $order)->with('error', 'Error, Transaction cant be completed with 0 amount.');
        }

        $mpesa_transaction_id = $cash_transaction_id = null;

        if ($request->payment_method == 'cash') {
            $cash_transaction_id =  'CSH' . date('Ymd') . '/' . sprintf("%03d", $order->id) . '/' . strtoupper(Str::random(3));
        }

        if ($request->payment_method == 'mpesa') {
            $mpesa_transaction_id =  'MPSHA' . date('Ymd') . '/' . sprintf("%03d", $order->id) . '/' . strtoupper(Str::random(3));
        }

        $transaction = Transaction::create([
            'fk_user' => auth()->user()->id,
            'fk_order' => $order->id,
            'payment_method' => $request->payment_method,
            'mpesa_transaction_id' => $mpesa_transaction_id,
            'cash_transaction_id' => $cash_transaction_id,
            'amount' => $order->amount,
        ]);

        if ($transaction) {
            $order->status = 'completed';
            $order->save();
            return redirect()->route('orders.show', $order)->with('success', 'Transaction recorded successfully.');
        }

        return redirect()->route('orders.show', $order)->with('error', 'Error, Transaction could not be completed successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        return response()
            ->json($transaction, 200, ['JSON_PRETTY_PRINT' => JSON_PRETTY_PRINT]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
