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
                <a href="#">Create</a>
            </li>
        </ul>
    </div>
    <div class="row">


        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Add|Edit Record</h4>
                        <a href="{{ route('orders.index') }}" class="btn btn-primary btn-round ml-auto">
                            <i class="flaticon-left-arrow-4 mr-2"></i>
                            View Records
                        </a>
                    </div>
                </div>
                <div class="card-body">

                    <!-- form -->
                    @include('cms.helpers.partials.feedback')
                    <form id="orders-create" action="@if(isset($order->id))  
                            {{ route('orders.update', ['order' => $order->id]) }}
                            @else {{ route('orders.store' ) }} @endif" method="post" >

                        @csrf

                        @if(isset($order->id))
                        @method('PUT')
                        <input type="hidden" name="created_by" value="{{ auth()->id() }}">
                        @endif


                        <div class="row">
                        <div class="col-md-4">
                                <div class="form-group">
                                    <label for="order_number"> Order Number </label>
                                    <input id="order_number" type="text" readonly class="form-control " name="order_number" value="{{ $order->order_number ?? '' }}" required="true" />
                                    @error('order_number') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="order_date"> Order Date</label>
                                    <input id="order_date" type="date" class="form-control " name="order_date" value="@if(isset($order->id)) {{ $order->order_date->format('dd/mm/yyyy') ?? '23/05/2023' }} @endif" placeholder="Enter your input" required="true" />
                                    @error('order_date') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="customer">Customer</label>
                                    <select name="fk_customer" id="fk_customer" class="form-control">
                                        <option selected> -- select customer --</option>
                                        @forelse($customers as $customer)
                                        <option value="{{ $customer->id }}" @if(isset($order->id)) {{ $customer->id == $order->fk_customer ? 'selected' : '' }} @endif> {{ $customer->name }} </option>
                                        @empty
                                        <option selected disabled> -- No item -- </option>
                                        @endforelse
                                    </select>
                                    @error('fk_customer') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="customer">Status</label>
                                    <select name="fk_customer" id="fk_customer" class="form-control">
                                        <option selected> -- select customer --</option>
                                        <option value="pending" @if(isset($order->id)) {{$order->status == 'pending' ? 'selected' : ''}} @endif> Pending </option>
                                        <option value="processing" @if(isset($order->id)) {{$order->status == 'processing' ? 'selected' : ''}} @endif> Processing </option>
                                        <option value="completed" @if(isset($order->id)) {{$order->status == 'completed' ? 'selected' : ''}} @endif> Completed </option>
                                        <option value="canceled" @if(isset($order->id)) {{$order->status == 'canceled' ? 'selected' : ''}} @endif> Canceled </option>
                                    </select>
                                    @error('fk_customer') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="total_amount"> Total Amount </label>
                                    <input id="total_amount" type="number" min=0 class="form-control " name="total_amount" value="{{ $order->total_amount ?? '' }}" readonly required="true" />
                                    @error('total_amount') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="form-group">
                                <button class="btn btn-success btn-round float-right">Submit</button>
                                <a href="{{ route('orders.show', ['order'=>$order->id]) }}" class="btn btn-info btn-round float-left"> Add Items</a>
                            </div>
                        </div>
                    </form>
                    <!-- End form -->

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

        });

        // Validate the quantity input field
        $('#quantity').on('input', function() {
            var max = parseInt($(this).attr('max'), 10);
            var value = parseInt($(this).val(), 10);

            if (value > max) {
                $(this).val(max);
                value = max;
            }
            // total amount calculation
            var price = parseInt($("#price").val(), 10);
            let total = price * value;
            console.log(total);
            $('#total_amount').val(total)
        });

    });
</script>

@endpush