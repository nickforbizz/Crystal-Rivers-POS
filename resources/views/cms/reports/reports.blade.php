@extends('layouts.cms')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title"> Reports </h4>
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
                <a href="#">Reports</a>
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
        <div class="col-sm-12">
            <div class="items mb-3">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="list-group shadow mb-3">
                            <button type="button" class="list-group-item list-group-item-secondary" aria-current="true">Orders
                            </button>
                            <a href="{{ route('reports.orders') }}" class="list-group-item list-groupf-item-light nav-item "> View </a>
                        </div>
                    </div>
    
                    <div class="col-sm-4">
                        <div class="list-group shadow mb-3">
                            <button type="button" class="list-group-item list-group-item-secondary" aria-current="true"> Users
                            </button>
                            <a href="{{ route('reports.users') }}" class="list-group-item list-groupf-item-light nav-item "> View </a>
                        </div>
                    </div>
    
                    <div class="col-sm-4">
                        <div class="list-group shadow mb-3">
                            <button type="button" class="list-group-item list-group-item-secondary" aria-current="true"> Customer
                            </button>
                            <a href="#" class="list-group-item list-groupf-item-light nav-item "> View </a>
                        </div>
                    </div>
                </div>
    
                <div class="row">
                    <div class="col-sm-4">
                        <div class="list-group shadow mb-3">
                            <button type="button" class="list-group-item list-group-item-secondary" aria-current="true"> Suppliers
                            </button>
                            <a href="#" class="list-group-item list-groupf-item-light nav-item "> View </a>
                        </div>
                    </div>
    
                    <div class="col-sm-4">
                        <div class="list-group shadow mb-3">
                            <button type="button" class="list-group-item list-group-item-secondary" aria-current="true"> Products
                            </button>
                            <a href="{{ route('reports.products') }}" class="list-group-item list-groupf-item-light nav-item "> View </a>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
            <!-- .items -->
        </div>


    </div>
</div>
<!-- .page-inner -->

@endsection


@push('scripts')


<script>
    $(document).ready(function() {


    });
</script>

@endpush