<!-- resources/views/invoices/order_invoice.blade.php -->

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
        /* Add your custom styles here */
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .card {
            padding: 10px 25px;
        }

        .shadow {
            box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
        }
    </style>
</head>

<body>
    <div style="padding: 10px 25px; box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;">
        <div class="card-header">
            <h2>Invoice -- #{{ $order->order_number }} <b style="float: right;">{{ ucwords($order->status) }}</b></h2> 
            <hr>
            <p>Date: <b style="float: right;">{{ $order->order_date->format('Y-m-d') }}</b></p>
            <p >Customer: <b style="float: right;">{{ $order->customer->name }}</b></p>
            <p > _ <b style="float: right;">{{ $order->customer->email }}</b></p>
            <p > _ <b style="float: right;">{{ $order->customer->phone }}</b></p>

        </div>
    </div>

    <div style="padding: 10px 25px; box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;">
        <hr>
        <h4>Items Purchased</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->order_items as $item)
                <tr>
                    <td>{{ $item->product->title }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->unit_price, 2) }}</td>
                    <td>{{ number_format($item->amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 6px;">
            <hr>
            <p >Total Amount: <b style="float: right;">{{ number_format($order->total_amount, 2) }}</b></p>
        </div>

    </div>
</body>

</html>