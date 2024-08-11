<?php

namespace App\Http\Controllers\cms;

use App\Exports\PostReportExport;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{

    /**
     * index function
     *
     * @return void
     */
    public function index() {
        return view('cms.reports.reports');
    }


    /**
     * users function
     *
     * @param  Request       $request
     * @param  ReportService $reportService
     * @return void
     */
    public function users(Request $request, ReportService $reportService) {
        $selectedYear = $request->get('year', Carbon::now()->year);
        $report = $reportService->getCountByMonth(new User, $selectedYear);

        return view('cms.reports.users',[
            'chartData' => $report['chartData'],
            'years' => $report['years'],
            'selectedYear' => $selectedYear
        ]);
    }


    /**
     * orders function
     *
     * @param  Request       $request
     * @param  ReportService $reportService
     * @return void
     */
    public function orders(Request $request, ReportService $reportService) {
        $selectedYear = $request->get('year', Carbon::now()->year);
        $report = $reportService->getCountByMonth(new Order, $selectedYear);

        return view('cms.reports.orders',[
            'chartData' => $report['chartData'],
            'years' => $report['years'],
            'selectedYear' => $selectedYear
        ]);
    }


    /**
     * products function
     *
     * @param  Request       $request
     * @param  ReportService $reportService
     * @return void
     */
    public function products(Request $request, ReportService $reportService) {
        $selectedYear = $request->get('year', Carbon::now()->year);
        $report = $reportService->getCountByMonth(new Product, $selectedYear);

        return view('cms.reports.products',[
            'chartData' => $report['chartData'],
            'years' => $report['years'],
            'selectedYear' => $selectedYear
        ]);
    }


    /**
     * customers function
     *
     * @param  Request       $request
     * @param  ReportService $reportService
     * @return void
     */
    public function customers(Request $request, ReportService $reportService) {
        $selectedYear = $request->get('year', Carbon::now()->year);
        $report = $reportService->getCountByMonth(new Customer, $selectedYear);

        return view('cms.reports.customers',[
            'chartData' => $report['chartData'],
            'years' => $report['years'],
            'selectedYear' => $selectedYear
        ]);
    }


    /**
     * suppliers function
     *
     * @param  Request       $request
     * @param  ReportService $reportService
     * @return void
     */
    public function suppliers(Request $request, ReportService $reportService) {
        $selectedYear = $request->get('year', Carbon::now()->year);
        $report = $reportService->getCountByMonth(new Supplier, $selectedYear);

        return view('cms.reports.suppliers',[
            'chartData' => $report['chartData'],
            'years' => $report['years'],
            'selectedYear' => $selectedYear
        ]);
    }


    /**
     * index function
     *
     * @param  Request       $request
     * @param  ReportService $reportService
     * @return void
     */
    public function userReport(Request $request, ReportService $reportService)
    {
        $selectedYear = $request->get('year', Carbon::now()->year);

        try {
            $posts_report = $reportService->getCountByMonth(new Product, $selectedYear);
            $users_report = $reportService->getCountByMonth(new User, $selectedYear);
        } catch (\Throwable $th) {
            throw $th;
        }
        
        return view('cms.reports.index', [
            'postsChartData' => $posts_report['chartData'],
            'postsYears' => $posts_report['years'],
            'usersChartData' => $users_report['chartData'],
            'usersYears' => $users_report['years'],
            'selectedYear' => $selectedYear
        ]);
    }


    public function downloadCsv(Request $request)
    {
        $year = request('year', Carbon::now()->year);
        $data = Post::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
                    ->whereYear('created_at', $year)
                    ->groupBy('month')
                    ->get();
    
        $chartData = [];
        foreach ($data as $row) {
            $month = Carbon::create(null, $row->month)->format('F');
            $chartData[$month] = $row->count;
        }
    
        $fileName = 'post_report_'.$year;
    
        $export = new PostReportExport($data);
    
        return Excel::download($export, $fileName.'.xlsx');
    
    }

}
