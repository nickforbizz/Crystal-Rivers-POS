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
                <a href="#"> Customers </a>
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


        <div class="col-md-12 p-2">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h2> Customers Report</h2>
                        <a href="{{ route('reports.index') }}" class="btn btn-info btn-round ml-auto">
                            <i class="flaticon-left-arrow-3 mr-2"></i>
                            All Reports
                        </a>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="form-group col-8">
                            <label for="select_year">Select Year:</label>
                            <select class="form-control" id="select_year" name="year">
                                @foreach($years as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-4">
                            <a href="{{ route('reports.download.csv') }}" class="form-control btn btn-primary btn-round mt-4"> <i class="flaticon-down-arrow-2 mr-2"></i> Download CSV</a>

                        </div>

                    </div>

                    <canvas id="customerChart_Area" height="120"></canvas>


                </div>
                <!-- .card-body -->
            </div>
        </div>
        <!-- .col-md-6 p-2 -->



    </div>
</div>
<!-- .page-inner -->

@endsection


@push('scripts')

<!-- Chart JS -->
<script src="{{ asset('assets/js/plugin/chart.js/chart.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#select_year').change(function() {
            var year = $(this).val();
            window.location.href = '{{ route("reports.customers") }}?year=' + year;
        });

    });




    const selected_year = $('#select_year').val();
    var report_data = {!!json_encode($chartData) !!};
    loadChart(report_data, 'customerChart_Area', 'User', selected_year)

    
</script>

@endpush