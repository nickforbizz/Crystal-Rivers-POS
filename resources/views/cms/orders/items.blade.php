@extends('layouts.cms')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title"> Orders </h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="#">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">Orders</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">Add Items</a>
            </li>
        </ul>
    </div>
    <div class="row">


        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center ">
                        <h4 class="card-title">Add|Edit Record</h4>

                        <a href="{{ route('orders.index') }}" class="btn btn-primary btn-round ml-auto">
                            <i class="flaticon-left-arrow-4 mr-2"></i>
                            View Records
                        </a>
                        <a href="{{ route('orders.invoice', ['order'=>$order->id]) }}" class="btn btn-info btn-round ml-2">
                            <i class="flaticon-print mr-2"></i>
                            Print Invoice
                        </a>

                        <button class="btn @if($order->status != 'completed') btn-info @else btn-success @endif btn-round ml-2"  @if($order->status != 'completed') data-toggle="modal" data-target="#orderPayModal"   @endif id="pay_order">
                            <i class="flaticon-add mr-2"></i>
                            @if($order->status != 'completed') Pay @else Paid @endif
                        </button>



                    </div>
                </div>
                <div class="card-body">
                    <h4>@include('cms.helpers.partials.feedback')</h4>
                    <div class="items mb-3">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="list-group shadow mb-3">
                                    <button type="button" class="list-group-item list-group-item-secondary" aria-current="true">Order Number
                                    </button>
                                    <button type="button" class="list-group-item list-group-item-light"> {{ $order->order_ID}}</button>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="list-group shadow mb-3">
                                    <button type="button" class="list-group-item list-group-item-secondary" aria-current="true">Order Date
                                    </button>
                                    <button type="button" class="list-group-item list-group-item-light"> {{ $order->order_date->diffForHumans() }}</button>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="list-group shadow mb-3">
                                    <button type="button" class="list-group-item list-group-item-secondary" aria-current="true"> Customer
                                    </button>
                                    <a href="{{ route('customers.show', ['customer'=>$order->fk_customer]) }}" class="list-group-item list-groupf-item-light nav-item "> {{ $order->customer?->names}} </a>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="list-group shadow mb-3">
                                    <button type="button" class="list-group-item list-group-item-secondary" aria-current="true"> Status
                                    </button>
                                    <button type="button" class="list-group-item list-group-item-light"> {{ ucfirst($order->status)}}</button>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="list-group shadow mb-3">
                                    <button type="button" class="list-group-item list-group-item-secondary" aria-current="true"> Total Amount
                                    </button>
                                    <button type="button" class="list-group-item list-group-item-light"> {{ ucfirst($order->amount)}}</button>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <!-- .items -->


                    <div class="card">
                        <div class="card-header d-flex">
                            Items Ordered
                            @if($order->status != 'completed')
                            <button class="btn btn-secondary btn-round ml-auto" data-toggle="modal" data-target="#orderItemModal" id="new_orderItem">
                                <i class="flaticon-add mr-2"></i>
                                Add Item
                            </button>
                            @else
                            <button class="btn btn-sm btn-success btn-round ml-auto" >
                                Order Completed
                            </button>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">

                                <table id="tb_order_items" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th> Product </th>
                                            <th> Quantity </th>
                                            <th> Unit Price </th>
                                            <th> Amount </th>
                                            <th> Active </th>
                                            <th> Created At </th>
                                            <th> Action </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                        </div>
                    </div>
                    <!-- .card -->



                    <!-- Modal -->
                    <div class="modal fade" id="orderItemModal" tabindex="-1" role="dialog" aria-labelledby="orderItemModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h5 class="modal-title text-white" id="orderItemModalLabel">Add New Order</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <!-- form -->
                                    <form id="orders-create" action="{{ route('orderItems.store' ) }}" method="post">

                                        @csrf
                                        <input type="hidden" name="fk_order" value="{{ $order->id }}">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="product">Product</label>
                                                    <select name="fk_product" id="fk_product" class="form-control">
                                                        <option selected> -- select product --</option>
                                                        @forelse($products as $product)
                                                        <option value="{{ $product->id }}" data-quantity="{{ $product->quantity }}" data-price="{{ $product->price }}" @if(isset($order->id)) {{ $product->id == $order->fk_product ? 'selected' : '' }} @endif> {{ $product->title }} </option>
                                                        @empty
                                                        <option selected disabled> -- No item -- </option>
                                                        @endforelse
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="price"> Price </label>
                                                    <input id="price" type="text" readonly class="form-control " name="price" value="{{ $order->price ?? '' }}" required="true" />

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="quantity"> Quantity </label>
                                                    <input id="quantity" type="number" min=1 class="form-control " name="quantity" value="{{ $order->quantity ?? '' }}" placeholder="Enter your input" required="true" />

                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="total_amount"> Total Amount </label>
                                            <input id="total_amount" type="number" min=0 class="form-control " name="total_amount" value="{{ $order->total_amount ?? '' }}" readonly required="true" />

                                        </div>
                                        <div id="put"></div>

                                        <div class="">
                                            <hr>
                                            <div class="form-group">
                                                <button type="button" class="btn btn-round  btn-secondary  float-left" data-dismiss="modal">Close</button>
                                                <button class="btn btn-success btn-round btn-blodck float-right submit-form-btn">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- End form -->
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- .modal -->

                                        <!-- Payment Modal -->
                                        <div class="modal fade" id="orderPayModal" tabindex="-1" role="dialog" aria-labelledby="orderPayModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content border-0 shadow-lg rounded-3">
                                                <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title fw-bold" id="orderPayModalLabel">
                                                    Pay Order {{ $order->order_number }}
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                        
                                                <div class="modal-body p-4">
                                                <form id="orders-create" action="{{ route('transactions.store', $order) }}" method="POST" novalidate>
                                                    @csrf
                                                    <input type="hidden" name="fk_order" value="{{ $order->id }}">
                                        
                                                    <div class="mb-3">
                                                    <label for="payment_method" class="form-label fw-semibold">Payment Method</label>
                                                    <select name="payment_method" id="payment_method" class="form-select" required>
                                                        <option value="">Select Payment Method</option>
                                                        <option value="cash">💵 Cash</option>
                                                        <option value="mpesa">📱 M-PESA</option>
                                                        <option value="visa">💳 Visa</option>
                                                        <option value="mastercard">💳 Mastercard</option>
                                                        <option value="paypal">🌍 PayPal</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please select a payment method.</div>
                                                    </div>
                                        
                                                    <div id="extra-fields"></div>
                                        
                                                    <div class="mb-3">
                                                    <label for="amount" class="form-label fw-semibold">Amount (Ksh)</label>
                                                    <input type="number" class="form-control bg-light" id="amount" name="amount" value="{{$order->amount}}" min="1" readonly required>
                                                    <div class="invalid-feedback">Enter a valid amount.</div>
                                                    </div>
                                        
                                                    <div class="d-flex justify-content-between mt-4">
                                                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-dismiss="modal">
                                                        Close
                                                    </button>
                                                    <button type="submit" class="btn btn-success rounded-pill px-5 fw-bold">
                                                        Submit
                                                    </button>
                                                    </div>
                                                </form>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
  
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- .page-inner -->

@endsection


@push('scripts')
<script>
    $(document).ready(function() {

        $('#tb_order_items').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('orders.show', ['order'=>$order->id]) }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'fk_product'
                },
                {
                    data: 'quantity'
                },
                {
                    data: 'unit_price'
                },
                {
                    data: 'amount'
                },
                {
                    data: 'active'
                },
                {
                    data: 'created_at',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: true,
                    searchable: true
                },
            ]
        });
        // #tb_order_items

        
        $("#orders-create").submit(function (e) {
            let quantity = parseInt($("#quantity").val());
            if(quantity < 1){
                $("#orderItemModal").modal('hide');
                $(".submit-form-btn").html(`Submit`).attr('disabled', false);
                e.preventDefault();
                $.notify("Quantity cannot be 0",{
				type: 'danger',
				placement: {
					from: 'top',
					align: 'right'
				},
				time: 1000,
				delay: 0,
			    });
            }
        })



        $("#fk_product").trigger('change');

        $("#fk_product").change(function() {
            var selectedOption = $(this).find(':selected');
            var price = selectedOption.data('price');
            var quantity = selectedOption.data('quantity');
            console.log({
                quantity,
                price
            });
            $("#quantity").attr('max', quantity);
            $("#price").val(price);
            computeTotalAmt()

        });

        // Validate the quantity input field
        $('#quantity').on('input', function() {
            computeTotalAmt()
        });
        const paymentSelect = $('#payment_method');
    const extraFieldsDiv = $('#extra-fields');
    const form = $('#orders-create');

    paymentSelect.on('change', function () {
        const method = $(this).val();
        let html = '';
        switch (method) {
            case 'mpesa':
                html = `
                    <div class="form-group">
                        <label for="mpesa_number">M-PESA Number</label>
                        <input type="tel" class="form-control" name="mpesa_number" id="mpesa_number"
                            placeholder="Enter M-PESA number (e.g. 0712345678)"
                            pattern="^07[0-9]{8}$" required>
                        <div class="invalid-feedback">Enter a valid Kenyan M-PESA number (07XXXXXXXX).</div>
                    </div>`;
                break;
            case 'visa':
                html = `
                    <div class="form-group">
                        <label for="visa_card_number">Visa Card Number</label>
                        <input type="text" class="form-control" name="visa_card_number" id="visa_card_number"
                            placeholder="Enter 16-digit Visa card number"
                            pattern="^4[0-9]{12}(?:[0-9]{3})?$"
                            maxlength="19" required>
                        <div class="invalid-feedback">Enter a valid Visa card number.</div>
                    </div>
                    <div class="form-group">
                        <label for="visa_expiry">Expiry Date</label>
                        <input type="month" class="form-control" name="visa_expiry" id="visa_expiry" required>
                        <div class="invalid-feedback">Please select the card expiry date.</div>
                    </div>
                    <div class="form-group">
                        <label for="visa_cvv">CVV</label>
                        <input type="password" class="form-control" name="visa_cvv" id="visa_cvv"
                            maxlength="3" pattern="^[0-9]{3}$"
                            placeholder="Enter 3-digit CVV" required>
                        <div class="invalid-feedback">Enter a valid 3-digit CVV.</div>
                    </div>`;
                break;
            case 'mastercard':
                html = `
                    <div class="form-group">
                        <label for="mastercard_number">Mastercard Number</label>
                        <input type="text" class="form-control" name="mastercard_number" id="mastercard_number"
                            placeholder="Enter 16-digit Mastercard number"
                            pattern="^5[1-5][0-9]{14}$"
                            maxlength="19" required>
                        <div class="invalid-feedback">Enter a valid Mastercard number.</div>
                    </div>
                    <div class="form-group">
                        <label for="mastercard_expiry">Expiry Date</label>
                        <input type="month" class="form-control" name="mastercard_expiry" id="mastercard_expiry" required>
                        <div class="invalid-feedback">Please select the card expiry date.</div>
                    </div>
                    <div class="form-group">
                        <label for="mastercard_cvv">CVV</label>
                        <input type="password" class="form-control" name="mastercard_cvv" id="mastercard_cvv"
                            maxlength="3" pattern="^[0-9]{3}$"
                            placeholder="Enter 3-digit CVV" required>
                        <div class="invalid-feedback">Enter a valid 3-digit CVV.</div>
                    </div>`;
                break;
            case 'paypal':
                html = `
                    <div class="form-group">
                        <label for="paypal_email">PayPal Email</label>
                        <input type="email" class="form-control" name="paypal_email" id="paypal_email"
                            placeholder="example@domain.com" required>
                        <div class="invalid-feedback">Enter a valid PayPal email address.</div>
                    </div>`;
                break;
            default:
                html = ''; // cash or nothing
                break;
        }
        extraFieldsDiv.html(html);
    });

    // Bootstrap validation
    form.on('submit', function (e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        $(this).addClass('was-validated');
    });

    });

    async function editOrderItem(id, orderItemUrl, updateOrderItemUrl) {
        try {
            const response = await fetch(orderItemUrl);
            if (!response.ok) {
                throw new Error(`Response status: ${response.status}`);
            }

            const json = await response.json();
            console.log(json.orderItem);
            $("#fk_product").val(json.orderItem.fk_product).change()
            $("#price").val(json.orderItem.unit_price)
            $("#quantity").val(json.orderItem.quantity)
            $("#total_amount").val(json.orderItem.amount)
            $("#orderItemModal").modal();

            $('#orders-create').attr('action', updateOrderItemUrl)
            $("#put").html(`<input type="hidden" name="_method" value="PUT">`)
        } catch (error) {
            console.error(error.message);
        }
    }

    function computeTotalAmt() {
        var max = parseInt($('#quantity').attr('max'), 10);
        var value = parseInt($('#quantity').val(), 10);

        if (value > max) {
            $('#quantity').val(max);
            value = max;
        }
        // total amount calculation
        var price = parseInt($("#price").val(), 10);
        let total = price * value;
        console.log(total);
        $('#total_amount').val(total)
    }
</script>

@endpush