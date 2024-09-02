<?php

namespace App\Http\Controllers;

use App\Models\inventory;
use App\Models\network;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // dd(Auth::user());

        if (Auth::user()->status == 'Administrator' || Auth::user()->status == 'Super Admin' || Auth::user()->hirar == 'Manager' || Auth::user()->hirar == 'Deputy General Manager') {
            $assets = Inventory::all();

            // Aggregate data for asset growth per year and location
            $yearlyGrowth = $assets->filter(function ($item) {
                // Filter hanya data dengan acquisition_date tidak kosong
                return $item->acquisition_date !== '-';
            })->groupBy(function ($item) {
                // Menggunakan Carbon untuk mengurai acquisition_date yang valid dan lokasi
                return Carbon::parse($item->acquisition_date)->format('Y') . '_' . $item->location;
            })->map->count();

            // Convert to a format suitable for charts (if necessary)
            $yearlyGrowthFormatted = $yearlyGrowth->sortKeys()->map(function ($count, $year_location) {
                // Memisahkan tahun dan lokasi dari kunci yang digunakan untuk pengelompokan
                list($year, $location) = explode('_', $year_location);
                return ['year' => $year, 'location' => $location, 'count' => $count];
            })->values();

            // Aggregate data for the charts
            $statusCounts = $assets->groupBy('status')->map->count();
            $categoryStatusCounts = $assets->groupBy('asset_category')->map(function ($category) {
                return $category->groupBy('status')->map->count();
            });

            // Aggregate data for asset growth per month in the last year
            $oneYearAgo = Carbon::now()->subYear();
            $locations = ['Head Office', 'Office Kendari', 'Site Molore']; // Tambahkan lokasi yang Anda inginkan

            $monthlyGrowth = $assets->filter(function ($item) use ($oneYearAgo) {
                // Filter hanya data dengan acquisition_date tidak sama dengan '-'
                return $item->acquisition_date !== '-' && Carbon::parse($item->acquisition_date)->greaterThanOrEqualTo($oneYearAgo);
            })->groupBy(function ($item) {
                // Mengelompokkan data berdasarkan bulan dan lokasi
                return Carbon::parse($item->acquisition_date)->format('Y-m') . '|' . $item->location;
            })->map->count();

            // Ensure every month in the last year is represented, even if the count is zero
            $monthlyGrowthFormatted = collect();
            for ($i = 0; $i < 12; $i++) {
                $date = Carbon::now()->subMonths($i)->format('Y-m');
                foreach ($locations as $location) {
                    $key = $date . '|' . $location;
                    $monthlyGrowthFormatted->push([
                        'month' => $date,
                        'location' => $location,
                        'count' => $monthlyGrowth->get($key, 0)
                    ]);
                }
            }
            $monthlyGrowthFormatted = $monthlyGrowthFormatted->sortBy('month')->values();

            $inventory = inventory::join('disposes', 'inventories.id', '=', 'disposes.inv_id')
                ->select(
                    'inventories.asset_code',
                    'inventories.serial_number',
                    'inventories.useful_life',
                    'inventories.location',
                    'inventories.status',
                    'disposes.tanggal_penghapusan',
                    'disposes.note'
                )
                ->orderBy('disposes.tanggal_penghapusan', 'desc')
                ->take(5)
                ->get();

            $repair = inventory::join('repairstatuses', 'inventories.id', '=', 'repairstatuses.inv_id')
                ->select(
                    'inventories.asset_code',
                    'inventories.serial_number',
                    'inventories.useful_life',
                    'inventories.location',
                    'repairstatuses.status',
                    'repairstatuses.tanggal_kerusakan',
                    'repairstatuses.tanggal_pengembalian',
                    'repairstatuses.note'
                )
                ->orderBy('repairstatuses.tanggal_kerusakan', 'desc')
                ->take(5)
                ->get();
        } else {
            $assets = Inventory::where('location', Auth::user()->location)->get();

            // Aggregate data for asset growth per year
            $yearlyGrowth = $assets->filter(function ($item) {
                // Filter hanya data dengan acquisition_date tidak kosong
                return $item->acquisition_date !== '-';
            })->groupBy(function ($item) {
                // Menggunakan Carbon untuk mengurai acquisition_date yang valid
                return Carbon::parse($item->acquisition_date)->format('Y');
            })->map->count();

            // Convert to a format suitable for charts (if necessary)
            $yearlyGrowthFormatted = $yearlyGrowth->sortKeys()->map(function ($count, $year) {
                return ['year' => $year, 'count' => $count];
            })->values();

            // Aggregate data for the charts
            $statusCounts = $assets->groupBy('status')->map->count();
            $categoryStatusCounts = $assets->groupBy('asset_category')->map(function ($category) {
                return $category->groupBy('status')->map->count();
            });

            // Aggregate data for asset growth per month in the last year
            $oneYearAgo = Carbon::now()->subYear();
            $monthlyGrowth = $assets->filter(function ($item) use ($oneYearAgo) {
                // Filter hanya data dengan acquisition_date tidak sama dengan '-'
                return $item->acquisition_date !== '-' && Carbon::parse($item->acquisition_date)->greaterThanOrEqualTo($oneYearAgo);
            })->groupBy(function ($item) {
                // Menggunakan Carbon untuk mengurai acquisition_date yang valid
                return Carbon::parse($item->acquisition_date)->format('Y-m');
            })->map->count();

            // Ensure every month in the last year is represented, even if the count is zero
            $monthlyGrowthFormatted = collect();
            for ($i = 0; $i < 12; $i++) {
                $date = Carbon::now()->subMonths($i)->format('Y-m');
                $monthlyGrowthFormatted->push([
                    'month' => $date,
                    'count' => $monthlyGrowth->get($date, 0)
                ]);
            }
            $monthlyGrowthFormatted = $monthlyGrowthFormatted->sortBy('month')->values();

            // Query untuk mengambil data inventory yang telah dipindahkan
            $inventory = Inventory::join('disposes', 'inventories.id', '=', 'disposes.inv_id')
                ->select(
                    'inventories.asset_code',
                    'inventories.serial_number',
                    'inventories.useful_life',
                    'inventories.location',
                    'inventories.status',
                    'disposes.tanggal_penghapusan',
                    'disposes.note'
                )
                ->where('inventories.location', '=', Auth::user()->location)
                ->orderBy('disposes.tanggal_penghapusan', 'desc')
                ->take(5)
                ->get();

            // Query untuk mengambil data inventory yang perlu direparasi
            $repair = Inventory::join('repairstatuses', 'inventories.id', '=', 'repairstatuses.inv_id')
                ->select(
                    'inventories.asset_code',
                    'inventories.serial_number',
                    'inventories.useful_life',
                    'inventories.location',
                    'repairstatuses.status',
                    'repairstatuses.tanggal_kerusakan',
                    'repairstatuses.tanggal_pengembalian',
                    'repairstatuses.note'
                )
                ->where('inventories.location', '=', Auth::user()->location)
                ->orderBy('repairstatuses.tanggal_kerusakan', 'desc')
                ->take(5)
                ->get();
        }

        $networks = Network::orderBy('start_time', 'desc')->take(5)->get();

        // Get selected month from request or default to current month
        $selectedMonth = $request->input('month', now()->format('Y-m'));

        // Prepare Telkom downtime data
        $telkomDowntime = $this->calculateTelkomDowntime();
        $bommDowntime = $this->calculateBommDowntime();

        // Calculate total minutes in the selected month
        $totalMinutesInMonth = Carbon::parse($selectedMonth)->daysInMonth * 24 * 60;

        // Calculate downtime and normal operation minutes for Telkom
        $telkomDowntimeMinutes = $telkomDowntime;
        $telkomNormalOperationMinutes = $totalMinutesInMonth - $telkomDowntimeMinutes;

        // Prepare Telkom chart data
        $telkomChartData = [
            'labels' => ['Downtime', 'Normal Operation'],
            'data' => [$telkomDowntimeMinutes, $telkomNormalOperationMinutes]
        ];

        // Calculate downtime and normal operation minutes for BOMM
        $bommDowntimeMinutes = $bommDowntime;
        $bommNormalOperationMinutes = $totalMinutesInMonth - $bommDowntimeMinutes;

        // Prepare BOMM chart data
        $bommChartData = [
            'labels' => ['Downtime', 'Normal Operation'],
            'data' => [$bommDowntimeMinutes, $bommNormalOperationMinutes]
        ];

        return view('dashboard.index', [
            'statusCounts' => $statusCounts,
            'categoryStatusCounts' => $categoryStatusCounts,
            'yearlyGrowth' => $yearlyGrowthFormatted,
            'monthlyGrowth' => $monthlyGrowthFormatted,
            'inventory' => $inventory,
            'repair' => $repair,
            'network' => $networks,
            'telkomDowntime' => $telkomChartData,
            'bommDowntime' => $bommChartData,
            'selectedMonth' => $selectedMonth
        ]);
    }

    public function calculateTelkomDowntime()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        // Fetch records for Telkom from the last year
        $telkomNetworks = Network::where('provider', 'Telkom')
            ->whereMonth('start_time', $currentMonth)
            ->whereYear('start_time', $currentYear)
            ->get()
            ->map(function ($item) {
                if ($item->end_time) {
                    return Carbon::parse($item->end_time)->diffInMinutes(Carbon::parse($item->start_time));
                }
                return 0;
            })
            ->sum();

        return $telkomNetworks;
    }

    public function calculateBommDowntime()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        // Fetch records for Telkom from the last year
        $bommNetworks = Network::where('provider', 'Bomm Akses')
            ->whereMonth('start_time', $currentMonth)
            ->whereYear('start_time', $currentYear)
            ->get()
            ->map(function ($item) {
                if ($item->end_time) {
                    return Carbon::parse($item->end_time)->diffInMinutes(Carbon::parse($item->start_time));
                }
                return 0;
            })
            ->sum();

        return $bommNetworks;
    }
}
