<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\DeviceModel;
use App\Models\Distributor;
use App\Models\DistributorIn;
use App\Models\ModelKpi;
use App\Models\MonthlyKpi;
use App\Models\MonthlyKpiSlab;
use App\Models\Region;
use App\Models\Requisition;
use App\Models\Retail;
use App\Models\Territory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KpiReportController extends Controller
{
    public function monthly()
    {
        $distributor= Distributor::all();
        return view('kpi.monthlyReport' , compact('distributor'));
    }

    public function monthlyData(Request $request)
    {
        $draw   = $request->get('draw');
        $start  = $request->get('start', 0);
        $length = $request->get('length', 10);

        // Get requested month/year (default: current month)
        $monthYear = $request->month_year ?? now()->format('Y-m');
        $month = date('m', strtotime($monthYear));
        $year  = date('Y', strtotime($monthYear));

        // Build base query for distributors
        $distributors = Distributor::query();

        if ($request->filled('distributor_id')) {
            $distributors->where('id', $request->distributor_id);
        }

        $totalRecords = $distributors->count();

        // Pagination
        $distributors = $distributors->skip($start)->take($length)->get();

        $data = [];

        foreach ($distributors as $dist) {

            $monthlyKpi = MonthlyKpi::where('kpi_type', 'monthly')
                ->where('month_year', $monthYear)
                ->first();

            $target = $monthlyKpi ? $monthlyKpi->target_amount : 0;

            $totalAchieve = Requisition::where('distributor_id', $dist->id)
                ->whereYear('sales_approved_date', $year)
                ->whereMonth('sales_approved_date', $month)
                ->where('status', 1)
                ->sum('total_amount');

            $achievementPercent = $target > 0 ? ($totalAchieve / $target) * 100 : 0;

            $incentiveRate = 0;
            if ($monthlyKpi) {
                $slab = $monthlyKpi->slabs()
                    ->where('criteria_percent', '<=', $achievementPercent)
                    ->orderByDesc('criteria_percent')
                    ->first();
                if ($slab) {
                    $incentiveRate = $slab->incentive_rate;
                }
            }

            $incentiveAmount = ($target * $incentiveRate) / 100;

            $data[] = [
                'distributor_id'   => $dist->id,
                'distributor_name' => $dist->distributor_name,
                'target'           => number_format($target, 2),
                'buy_amount'           => $totalAchieve,
                'achievement'      => number_format($achievementPercent, 2) . '%',
                'incentive_rate'   => $incentiveRate . '%',
                'incentive_amount' => number_format($incentiveAmount, 2),
            ];
        }

        return response()->json([
            'draw'            => intval($draw),
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data'            => $data,
        ]);
    }

    public function quarterly()
    {
        $distributor= Distributor::all();
        return view('kpi.quarterlyReport' , compact('distributor'));
    }

    public function quarterlyData(Request $request)
    {
        // Log::info();

        $draw   = $request->get('draw');
        $start  = $request->get('start', 0);
        $length = $request->get('length', 10);

        $year     = $request->month_year ?? now()->format('Y');
        $quarter  = $request->quarter;

        $quarterMonths = [
            1 => [1, 2, 3],
            2 => [4, 5, 6],
            3 => [7, 8, 9],
            4 => [10, 11, 12],
        ];
        $months = $quarterMonths[$quarter] ?? [];

        $distributors = Distributor::query();
        if ($request->filled('distributor_id')) {
            $distributors->where('id', $request->distributor_id);
        }

        $totalRecords = $distributors->count();
        $distributors = $distributors->skip($start)->take($length)->get();

        $data = [];

        foreach ($distributors as $dist) {

            $target = MonthlyKpi::where('kpi_type', 'monthly')
                ->where('kpi_for', 'Distributor')
                ->where(function ($q) use ($year, $months) {
                    foreach ($months as $m) {
                        $q->orWhere('month_year', $year . '-' . str_pad($m, 2, '0', STR_PAD_LEFT));
                    }
                })
                ->sum('target_amount');

            $totalAchieve = Requisition::where('distributor_id', $dist->id)
                ->whereYear('sales_approved_date', $year)
                ->whereIn(DB::raw('MONTH(sales_approved_date)'), $months)
                ->where('status', 1)
                ->sum('total_amount');

            $achievementPercent = $target > 0 ? ($totalAchieve / $target) * 100 : 0;

            $quarterlyKpi = MonthlyKpi::where('kpi_type', 'quarterly')
                ->where('month_year', $year)
                ->where('quarter', $quarter)
                ->first();

            $incentiveRate = 0;

            if ($quarterlyKpi) {

                $slab = $quarterlyKpi->slabs()
                    ->where('criteria_percent', '<=', $achievementPercent)
                    ->orderByDesc('criteria_percent')
                    ->first();

                if ($slab) {
                    $incentiveRate = $slab->incentive_rate;
                }
            }

            $incentiveAmount = ($target * $incentiveRate) / 100;

            $data[] = [
                'distributor_id'   => $dist->id,
                'distributor_name' => $dist->distributor_name,
                'target'           => number_format($target, 2),
                'buy_amount'       => number_format($totalAchieve, 2),
                'achievement'      => number_format($achievementPercent, 2) . '%',
                'incentive_rate'   => $incentiveRate . '%',
                'incentive_amount' => number_format($incentiveAmount, 2),
            ];
        }

        return response()->json([
            'draw'            => intval($draw),
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data'            => $data,
        ]);
    }

    public function model()
    {
        $distributor= Distributor::all();
        $retail = Retail::all();
        $model = DeviceModel::all();
        return view('kpi.modelReport' , compact('distributor', 'retail' , 'model'));
    }

    public function modelData(Request $request)
    {
        // Log::info('Model KPI Report Request', $request->all());
        $draw   = $request->get('draw');
        $start  = $request->get('start', 0);
        $length = $request->get('length', 10);

        $monthYear = $request->month_year ?? now()->format('Y-m');

        if (!$request->filled('distributor_id') && !$request->filled('retail_id') && !$request->filled('model')) {
            return response()->json([
                'draw' => intval($draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
            ]);
        }

        $data = [];

        if ($request->filled('distributor_id')) {
            $kpi = ModelKpi::where('kpi_for', 'Distributor')
                ->where('month_year', $monthYear)
                ->where('model_name', $request->model)
                ->first();

            $distributor = Distributor::find($request->distributor_id);

            if ($kpi && $distributor) {

                $target = $kpi->target_quantity;

                $sellQuantity = DistributorIn::where('distributor_id', $distributor->id)
                    ->where('model', $request->model)
                    ->where('status', 0)
                    ->whereNotNull('distributor_out')
                    ->whereRaw("DATE_FORMAT(distributor_out, '%Y-%m') = ?", [$monthYear])
                    ->count();

                $achievementPercent = $target > 0 ? ($sellQuantity / $target) * 100 : 0;

                $slab = $kpi->slabs()
                    ->where('criteria_percent', '<=', $achievementPercent)
                    ->orderByDesc('criteria_percent')
                    ->first();

                $incentiveAmountPerDevice = $slab ? $slab->incentive_amount : 0;

                $totalIncentive = $sellQuantity * $incentiveAmountPerDevice;

                $data[] = [
                    'model' => $request->model,
                    'distributor_id' => $distributor->id,
                    'distributor_name' => $distributor->distributor_name,
                    'retail_id' => 'N/A',
                    'retail_name' => 'N/A',
                    'target_quantity' => $target,
                    'sell_quantity' => $sellQuantity,
                    'achievement' => number_format($achievementPercent, 2) . '%',
                    'incentive_amount' => $incentiveAmountPerDevice,
                    'total_incentive_amount' => $totalIncentive,
                ];
            }
        }

        if ($request->filled('retail_id')) {
            $kpi = ModelKpi::where('kpi_for', 'Retailer')
                ->where('month_year', $monthYear)
                ->where('model_name', $request->model)
                ->first();

            $retail = Retail::find($request->retail_id);

            if ($kpi && $retail) {
                $target = $kpi->target_quantity;

                $sellQuantity = DistributorIn::where('retail_id', $retail->id)
                    ->where('model', $request->model)
                    ->where('retail_status', 0)
                    ->whereNotNull('retail_out')
                    ->whereRaw("DATE_FORMAT(retail_out, '%Y-%m') = ?", [$monthYear])
                    ->count();

                $achievementPercent = $target > 0 ? ($sellQuantity / $target) * 100 : 0;

                $slab = $kpi->slabs()
                    ->where('criteria_percent', '<=', $achievementPercent)
                    ->orderByDesc('criteria_percent')
                    ->first();

                $incentiveAmountPerDevice = $slab ? $slab->incentive_amount : 0;

                $totalIncentive = $sellQuantity * $incentiveAmountPerDevice;

                $data[] = [
                    'model' => $request->model,
                    'distributor_id' => 'N/A',
                    'distributor_name' => 'N/A',
                    'retail_id' => $retail->id,
                    'retail_name' => $retail->retail_name,
                    'target_quantity' => $target,
                    'sell_quantity' => $sellQuantity,
                    'achievement' => number_format($achievementPercent, 2) . '%',
                    'incentive_amount' => $incentiveAmountPerDevice,
                    'total_incentive_amount' => $totalIncentive,
                ];
            }
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => count($data),
            'recordsFiltered' => count($data),
            'data' => $data,
        ]);
    }

    public function regionAreaTerritory()
    {
        $region= Region::all();
        $area = Area::all();
        $territory = Territory::all();
        return view('kpi.regionAreaTerritory' , compact('region', 'area', 'territory'));
    }

    public function regionData(Request $request)
    {
        // Log::info('Region KPI Report Request', $request->all());

        $draw   = $request->get('draw');
        $start  = $request->get('start', 0);
        $length = $request->get('length', 10);

        $reportType = $request->get('report_type', 'month');

        $distributors = Distributor::query();

        if ($request->filled('region_id')) {
            $distributors->where('region_id', $request->region_id);
        }
        if ($request->filled('area_id')) {
            $distributors->where('area_id', $request->area_id);
        }
        if ($request->filled('territory_id')) {
            $distributors->where('territory_id', $request->territory_id);
        }

        $totalRecords = $distributors->count();
        $distributorIds = $distributors->pluck('id');
        $distributorCount = $distributorIds->count();

        $target = 0;
        $buyAmount = 0;
        $achievementPercent = 0;
        $monthYear = null;
        $year = null;
        $quarter = null;
        // Log::info( $distributorCount);
        if ($reportType === 'month') {

            $monthYear = $request->month_year ?? now()->format('Y-m');
            $month = date('m', strtotime($monthYear));
            $year  = date('Y', strtotime($monthYear));

            $singleTarget = MonthlyKpi::where('kpi_type', 'monthly')
                ->where('kpi_for', 'Distributor')
                ->where('month_year', $monthYear)
                ->value('target_amount') ?? 0;
            // Log::info( $singleTarget);

            $target = $singleTarget * $distributorCount;

            $buyAmount = Requisition::whereIn('distributor_id', $distributorIds)
                ->whereYear('sales_approved_date', $year)
                ->whereMonth('sales_approved_date', $month)
                ->where('status', 1)
                ->sum('total_amount');

            } elseif ($reportType === 'quarter') {
                $year = $request->year ?? now()->year;
                $quarter = $request->quarter;

                $quarters = [
                    1 => [1, 2, 3],
                    2 => [4, 5, 6],
                    3 => [7, 8, 9],
                    4 => [10, 11, 12],
                ];
                $months = $quarters[$quarter] ?? [];

                $monthStrings = array_map(function ($m) use ($year) {
                    return sprintf('%04d-%02d', $year, $m);
                }, $months);

                $singleTarget = MonthlyKpi::where('kpi_type', 'monthly')
                    ->where('kpi_for', 'Distributor')
                    ->whereIn('month_year', $monthStrings)
                    ->sum('target_amount');

                $target = $singleTarget * $distributorCount;

                $buyAmount = Requisition::whereIn('distributor_id', $distributorIds)
                    ->whereYear('sales_approved_date', $year)
                    ->whereIn(DB::raw('MONTH(sales_approved_date)'), $months)
                    ->where('status', 1)
                    ->sum('total_amount');
            }

        if ($target > 0) {
            $achievementPercent = ($buyAmount / $target) * 100;
        }

        $data[] = [
            'month_year'       => ($request->report_type === 'quarter')
                                ? ($year ?? 'N/A')
                                : $monthYear,
            'year'             => $year,
            'quarter'          => $quarter,
            'target_amount'    => number_format($target, 2),
            'buy_amount'       => number_format($buyAmount, 2),
            'achievement'      => number_format($achievementPercent, 2) . '%',
        ];

        return response()->json([
            'draw'            => intval($draw),
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data'            => $data,
        ]);
    }

    public function modelRsm()
    {
        $region= Region::all();
        $area = Area::all();
        $territory = Territory::all();
        $model = DeviceModel::all();
        return view('kpi.modelRsm' , compact('region', 'area', 'territory' , 'model'));
    }

    public function modelRsmData(Request $request)
    {
        Log::info('Region KPI Report Request', $request->all());

        $draw   = $request->get('draw');
        $start  = $request->get('start', 0);
        $length = $request->get('length', 10);

        $distributors = Distributor::query();

        if ($request->filled('region_id')) {
            $distributors->where('region_id', $request->region_id);
        }
        if ($request->filled('area_id')) {
            $distributors->where('area_id', $request->area_id);
        }
        if ($request->filled('territory_id')) {
            $distributors->where('territory_id', $request->territory_id);
        }

        $totalRecords = $distributors->count();
        $distributorIds = $distributors->pluck('id');
        $distributorCount = $distributorIds->count();

        $monthYear = $request->month_year ?? now()->format('Y-m');
        $modelId   = $request->model;

        $target = 0;
        $sellQuantity = 0;
        $achievementPercent = 0;

        $singleTarget = ModelKpi::where('kpi_for', 'Distributor')
            ->where('month_year', $monthYear)
            ->where('model_name', $modelId)
            ->value('target_quantity') ?? 0;

        $target = $singleTarget * $distributorCount;

        $sellQuantity = DistributorIn::where('model', $modelId)
            ->whereIn('distributor_id', $distributorIds)
            ->where('status', 0)
            ->whereNotNull('distributor_out')
            ->whereRaw("DATE_FORMAT(distributor_out, '%Y-%m') = ?", [$monthYear])
            ->count();

        if ($target > 0) {
            $achievementPercent = ($sellQuantity / $target) * 100;
        }

        $data = [[
            'month_year'      => $monthYear,
            'target_quantity' => number_format($target, 2),
            'sell_quantity'   => number_format($sellQuantity, 2),
            'achievement'     => number_format($achievementPercent, 2) . '%',
        ]];

        return response()->json([
            'draw'            => intval($draw),
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data'            => $data,
        ]);
    }

}
