<?php

namespace App\Http\Controllers\Process;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    /**
     * Generate report for admin users
     *
     * @param Request $request
     * @param string $type
     * @return \Illuminate\Http\Response
     */
    public function generateAdminReport(Request $request, $type)
    {
        // Get time period filter
        $period = $request->input('period', 'month');
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);
        
        // Set date range based on period
        if ($period === 'year') {
            $startDate = Carbon::createFromDate($year, 1, 1)->startOfDay();
            $endDate = Carbon::createFromDate($year, 12, 31)->endOfDay();
            $dateLabel = $year;
        } else {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay();
            $dateLabel = Carbon::createFromDate($year, $month, 1)->format('F Y');
        }
        
        // Generate different reports based on type
        switch ($type) {
            case 'sales':
                return $this->generateSalesReport($startDate, $endDate, $dateLabel);
                break;
            case 'orders':
                return $this->generateOrdersReport($startDate, $endDate, $dateLabel);
                break;
            case 'appointments':
                return $this->generateAppointmentsReport($startDate, $endDate, $dateLabel);
                break;
            case 'customers':
                return $this->generateCustomersReport($startDate, $endDate, $dateLabel);
                break;
            case 'products':
                return $this->generateProductsReport($startDate, $endDate, $dateLabel);
                break;
            default:
                return redirect()->back()->with('error', 'Invalid report type');
        }
    }
    
    // Rest of the code remains the same
    // ...
}