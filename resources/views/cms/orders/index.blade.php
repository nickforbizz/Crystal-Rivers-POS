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
                <a href="#"> Orders</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">Index</a>
            </li>
        </ul>
    </div>
    <div class="row">


        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">List of Available Record(s)</h4>
                        @can('create order')
                        <button class="btn btn-primary btn-round ml-auto" data-toggle="modal" data-target="#orderModal" id="new_order">
                            <i class="flaticon-add mr-2"></i>
                            New Order
                        </button>
                        @endcan
                    </div>
                </div>
                <div class="card-body">


                    <div class="table-responsive">
                        @include('cms.helpers.partials.feedback')
                        <table id="tb_orders" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th> Customer</th>
                                    <th> Amount </th>
                                    <th> Order Date </th>
                                    <th> Active </th>
                                    <th> Created At </th>
                                    <th> Action </th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h5 class="modal-title text-white" id="orderModalLabel">Add New Order</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <form id="orders-create" action="{{ route('orders.store' ) }} " method="post">
                                    <div class="modal-body">


                                        @csrf
                                        <div class="form-group">
                                            <label for="customer"> Select Customer to Continue </label>
                                            <select name="fk_customer" id="fk_customer" class="form-control" required>
                                                <option selected> -- select product --</option>
                                                @forelse($customers as $customer)
                                                <option value="{{ $customer->id }}" > {{ $customer->names }} | {{ $customer->email }} </option>
                                                @empty
                                                <option selected disabled> -- No customer -- </option>
                                                @endforelse
                                            </select>
                                            @error('fk_customer') <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="order_date">  Date Order Initiated</label>
                                            <input id="order_date" type="date" class="form-control " name="order_date" value="" placeholder="Enter your input" required="true" />
                                            @error('order_date') <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>


                                    </div>
                                    <!-- .modal-body -->
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-round  btn-secondary  float-left" data-dismiss="modal">Close</button>
                                        <button class="btn btn-success btn-round submit-form-btn float-right">Submit</button>
                                    </div>
                                </form>
                                <!-- End form -->
                                
                            </div>
                        </div>
                    </div>
                    <!-- .modal -->


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
        $('#tb_orders').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('orders.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'customer'
                },
                {
                    data: 'amount'
                },
                {
                    data: 'order_date'
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
        // #tb_orders

      
    });
</script>

@endpush