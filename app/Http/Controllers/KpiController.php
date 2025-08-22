<?php

namespace App\Http\Controllers;

use App\Models\DeviceModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\MonthlyKpi;
use App\Models\MonthlyKpiSlab;
use App\Models\ModelKpi;
use App\Models\ModelKpiSlab;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class KpiController extends Controller
{

    public function index()
    {
        return view('kpi.index');
    }

    public function create()
    {
        $model = DeviceModel::all();
        return view('kpi.create' , compact('model'));
    }

    public function store(Request $request)
    {
        // Log::info('KPI Store Request', $request->all());

        $rules = [
            'kpi_type' => 'required|in:monthly,quarterly,model',
        ];

        if ($request->kpi_type === 'monthly') {
            $rules = array_merge($rules, [
                'monthly_month_year' => [
                    'required',
                    Rule::unique('monthly_kpi', 'month_year')->where(function ($query) {
                        return $query->where('kpi_type', 'monthly');
                    }),
                ],
                'monthly_target_amount' => 'required|numeric|min:0',
                'monthly_criteria_percent' => 'required|array|min:1',
                'monthly_criteria_percent.*' => 'required|numeric|min:0',
                'monthly_incentive_rate' => 'required|array|min:1',
                'monthly_incentive_rate.*' => 'required|numeric|min:0',
                'monthly_kpi_for'  => 'required|in:Distributor,Retailer',
            ]);
        }

        if ($request->kpi_type === 'quarterly') {
            $rules = array_merge($rules, [
                'quarterly_quarter' => [
                    'required',
                    'integer',
                    'in:1,2,3,4',
                    Rule::unique('monthly_kpi', 'quarter')->where(function ($query) use ($request) {
                        return $query->where('kpi_type', 'quarterly')
                                    ->where('month_year', $request->quarterly_year);
                    }),
                ],
                'quarterly_year' => 'required|integer|min:2000|max:2100',
                'quarterly_criteria_percent' => 'required|array|min:1',
                'quarterly_criteria_percent.*' => 'required|numeric|min:0',
                'quarterly_incentive_rate' => 'required|array|min:1',
                'quarterly_incentive_rate.*' => 'required|numeric|min:0',
            ]);
        }

        if ($request->kpi_type === 'model') {
            $rules = array_merge($rules, [
                'model_id' => [
                    'required',
                    'integer',
                    'exists:models,id',
                    Rule::unique('model_kpi')->where(function ($query) use ($request) {
                        return $query->where('month_year', $request->model_month_year)
                                     ->where('kpi_for', $request->model_kpi_for);
                    }),
                ],
                'model_name' => 'required|string|max:255',
                'model_month_year' => 'required',
                'model_target_quantity' => 'required|numeric|min:0',
                'model_criteria_percent' => 'required|array|min:1',
                'model_criteria_percent.*' => 'required|numeric|min:0',
                'model_incentive_amount' => 'required|array|min:1',
                'model_incentive_amount.*' => 'required|numeric|min:0',
                'model_kpi_for'  => 'required|in:Distributor,Retailer',
            ]);
        }
        $messages = [
            'quarterly_quarter.unique' => 'This Quarter is already taken for the selected year.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // Log::warning('KPI Validation Failed', $validator->errors()->toArray());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            if ($request->kpi_type === 'monthly') {
                $monthlyKpi = MonthlyKpi::create([
                    'kpi_type'     => 'monthly',
                    'kpi_for' => $request->monthly_kpi_for,
                    'target_amount'=> (float) $request->monthly_target_amount,
                    'month_year'   => $request->monthly_month_year,
                ]);

                collect($request->monthly_criteria_percent)->each(function ($criteria, $index) use ($monthlyKpi, $request) {
                    MonthlyKpiSlab::create([
                        'monthly_kpi_id'   => $monthlyKpi->id,
                        'criteria_percent' => (float) $criteria,
                        'incentive_rate'   => (float) ($request->monthly_incentive_rate[$index] ?? 0),
                    ]);
                });
            }

            if ($request->kpi_type === 'quarterly') {
                $quarterlyKpi = MonthlyKpi::create([
                    'kpi_type'     => 'quarterly',
                    'quarter'    => $request->quarterly_quarter,
                    'month_year' => $request->quarterly_year,
                ]);

                collect($request->quarterly_criteria_percent)->each(function ($criteria, $index) use ($quarterlyKpi, $request) {
                    MonthlyKpiSlab::create([
                        'monthly_kpi_id'   => $quarterlyKpi->id,
                        'criteria_percent' => (float) $criteria,
                        'incentive_rate'   => (float) ($request->quarterly_incentive_rate[$index] ?? 0),
                    ]);
                });
            }

            if ($request->kpi_type === 'model') {
                $modelKpi = ModelKpi::create([
                    'model_id' => $request->model_id,
                    'model_name' => $request->model_name,
                    'target_quantity' => (float) $request->model_target_quantity,
                    'month_year' => $request->model_month_year,
                    'kpi_for' => $request->model_kpi_for,
                ]);

                collect($request->model_criteria_percent)->each(function ($criteria, $index) use ($modelKpi, $request) {
                    ModelKpiSlab::create([
                        'model_kpi_id' => $modelKpi->id,
                        'criteria_percent' => (float) $criteria,
                        'incentive_amount' => (float) ($request->model_incentive_amount[$index] ?? 0),
                    ]);
                });
            }

            DB::commit();
            return redirect()->back()->with('success', 'KPI saved successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            // Log::error('KPI Save Error', ['exception' => $e]);
            return redirect()->back()->with('error', 'Something went wrong, please try again.');
        }
    }

    public function monthly()
    {
        // $user = auth()->user();

        // $retails = Retail::all();


        return view('kpi.index');
    }

    public function monthlyData(Request $request)
    {
        $type = $request->get('type');

        $query = MonthlyKpi::with('slabs');

        if ($request->type === 'quarterly') {
            $query->where('kpi_type', 'quarterly');
        } elseif ($request->type === 'monthly') {
            $query->where('kpi_type', 'monthly');
        }

        $kpis = $query->get();
        return response()->json(['data' => $kpis]);
    }

    public function destroy($id)
    {
        try {
            $kpi = MonthlyKpi::findOrFail($id);

            // Delete related slabs
            $kpi->slabs()->delete();

            // Delete the KPI itself
            $kpi->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'KPI and its related slabs deleted successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete KPI: ' . $e->getMessage()
            ], 500);
        }
    }

    public function model()
    {
        // $user = auth()->user();

        // $retails = Retail::all();


        return view('kpi.model');
    }

    public function modelData()
    {
        $data = ModelKpi::with('slabs')->get();
        return response()->json(['data' => $data]);
    }

    public function destroyModel($id)
    {
        try {
            $kpi = ModelKpi::findOrFail($id);

            $kpi->slabs()->delete();

            $kpi->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'KPI and its related slabs deleted successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete KPI: ' . $e->getMessage()
            ], 500);
        }
    }

}
