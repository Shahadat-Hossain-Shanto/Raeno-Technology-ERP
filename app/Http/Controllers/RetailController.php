<?php

namespace App\Http\Controllers;

use App\Models\DeviceModel;
use App\Models\DistributorIn;
use App\Models\District;
use App\Models\Upazila;
use App\Models\Retail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\ImeiInfo;
use App\Models\Distributor;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Support\Facades\Validator;

class RetailController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $retails = Retail::all();

        if (!empty($user->distributor)) {
            $distributor = Distributor::where('id', $user->distributor)->first();

            if ($distributor) {
                $retails = Retail::where('distributor_id', $distributor->id)->get();
            }
        }

        return view('retail.index', compact('retails'));
    }

    // Show create form
    public function create()
    {
        $user = auth()->user();

        $districts = District::all();
        $upazilas = Upazila::all();
        if (!empty($user->distributor)) {
            $distributor = Distributor::where('id', $user->distributor)->get();
        } else {
            $distributor = Distributor::all();
        }
        return view('retail.create', compact('districts' , 'upazilas' , 'distributor'));
    }

    // Store new
    public function store(Request $request)
    {
        $validated = $request->validate([
            'retail_name'           => 'required|string|max:100',
            'owner_name'            => 'nullable|string|max:100',
            'nid'                   => 'nullable|string|max:20',
            'contact_no'            => 'required|string|max:20',
            'email'                 => 'nullable|email|max:100',
            'retail_address'        => 'nullable|string',
            'type'                  => 'nullable|string|in:Sole Proprietorship,Partnership,Private Ltd. Company',
            'trade_license_no'      => 'nullable|string|max:50',
            'trade_license_validity'=> 'nullable|date',
            'tin'                   => 'nullable|string|max:20',
            'bkash_no'              => 'nullable|string|max:20',
            'district_id'           => 'required|max:10',
            'upazila_id'            => 'required|max:10',
            'distributor_id'        => 'required|max:10',
        ]);

        Retail::create($validated);

        return redirect()->route('retails.index')
            ->with('success', 'Retail created successfully.');
    }

    // Show edit form
    public function edit(Retail $retail)
    {
        $user = auth()->user();

        $districts = District::all();
        $upazilas = Upazila::all();
        $retail = Retail::with('upazila')->findOrFail($retail->id);
        if (!empty($user->distributor)) {
            $distributor = Distributor::where('id', $user->distributor)->get();
        } else {
            $distributor = Distributor::all();
        }
        return view('retail.edit', compact('retail' , 'districts', 'upazilas' , 'distributor'));
    }

    // Update existing
    public function update(Request $request, Retail $retail)
    {
        $validated = $request->validate([
            'retail_name'            => 'required|string|max:100',
            'owner_name'             => 'nullable|string|max:100',
            'nid'                    => 'nullable|string|max:20',
            'contact_no'             => 'required|string|max:20',
            'email'                  => 'nullable|email|max:100',
            'retail_address'         => 'nullable|string',
            'type'                   => 'nullable|string|in:Sole Proprietorship,Partnership,Private Ltd. Company',
            'trade_license_no'       => 'nullable|string|max:50',
            'trade_license_validity' => 'nullable|date',
            'tin'                    => 'nullable|string|max:20',
            'bkash_no'               => 'nullable|string|max:20',
            'district_id'            => 'required|max:10',
            'upazila_id'             => 'required|max:10',
            'distributor_id'        => 'required|max:10',
        ]);

        $retail->update($validated);

        return redirect()->route('retails.index')
            ->with('success', 'Retail updated successfully.');
    }

    // Delete gallery item
    public function destroy(Retail $retail)
    {
        $retail->delete();
        return redirect()->route('retails.index')->with('success', 'Retail deleted successfully.');
    }

    public function data()
    {
        $user = auth()->user();

        // Start query
        $query = Retail::with(['district', 'upazila', 'distributor']);

        if (!empty($user->distributor)) {
            $distributor = Distributor::where('id', $user->distributor)->first();

            if ($distributor ) {
                $query->where('distributor_id', $distributor->id);
            }
        }

        $retails = $query->get();

        return response()->json(['data' => $retails]);
    }

    public function getUpazilasByDistrictId($district_id)
    {
        $upazilas = Upazila::where('district_id', $district_id)->select('id', 'name')->get();
        return response()->json($upazilas);
    }

    public function stock()
    {
        // $retails= Retail::all();
        return view('retail.stock');
    }

    public function stockData()
    {
        $user = auth()->user();
        $query = DistributorIn::with('retail:id,id,retail_name');

        if (!empty($user->distributor)) {
            $distributor = Distributor::select('id')->where('id', $user->distributor)->first();

            if ($distributor) {
                $retailIds = Retail::where('distributor_id', $distributor->id)->pluck('id');

                $query->whereIn('retail_id', $retailIds);
            } else {
                return response()->json(['data' => []]);
            }
        } else {
            $query->whereNotNull('retail_id');
        }

        $distributorIns = $query->get();

        $data = $distributorIns->map(function ($item) {
            return [
                'imei_1'          => $item->imei_1,
                'imei_2'          => $item->imei_2,
                'serial_number'   => $item->serial_number,
                'product_name'    => $item->product_name,
                'brand'           => $item->brand,
                'manufacturer'    => $item->manufacturer,
                'model'           => $item->model,
                'variant'         => $item->variant,
                'retail_status'   => $item->retail_status,
                'distributor_out' => $item->distributor_out,
                'retail_out'      => $item->retail_out,
                'retail_id'       => $item->retail_id,
                'retail_name'     => $item->retail->retail_name ?? 'N/A',
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function sell()
    {
        $user = auth()->user();

        $query = DistributorIn::where('retail_status', 1);

        if (!empty($user->distributor)) {
            $distributor = Distributor::select('id')->where('id', $user->distributor)->first();

            if ($distributor) {
                $retailIds = Retail::where('distributor_id', $distributor->id)->pluck('id');

                $query->whereIn('retail_id', $retailIds);
            } else {
                $imeis = collect();
                return view('retail.sell', compact('imeis'));
            }
        }

        $imeis = $query->get();

        return view('retail.sell', compact('imeis'));
    }

    public function getImeiInfo($imei)
    {
        $user = auth()->user();

        $imeiData = DistributorIn::with('retail')
            ->where('imei_1', $imei)
            ->first();

        if (!$imeiData) {
            return response()->json(['message' => 'IMEI not found'], 422);
        }

        if ($user->distributor && $user->distributor !== $imeiData->distributor_id) {
            return response()->json(['message' => 'This IMEI is not in your stock'], 422);
        }

        if ($imeiData->retail_status == 0) {
            return response()->json(['message' => 'Stock Out'], 422);
        }

        return response()->json([
            'imei_1'        => $imeiData->imei_1,
            'imei_2'        => $imeiData->imei_2,
            'serial_number' => $imeiData->serial_number,
            'product_name'  => $imeiData->product_name,
            'model'         => $imeiData->model,
            'variant'       => $imeiData->variant,
            'brand'         => $imeiData->brand ?? null,
            'manufacturer'  => $imeiData->manufacturer ?? null,
            'retail_id'     => $imeiData->retail_id,
            'retail_name'   => $imeiData->retail->retail_name ?? 'N/A',
        ]);
    }

    public function sellDevice(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.imei_1' => 'required|string|distinct',
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->items as $item) {
                $imei = $item['imei_1'];
                $now = Carbon::now();

                DistributorIn::where('imei_1', $imei)->update([
                    'retail_status' => 0,
                    'retail_out' => $now,
                ]);

                ImeiInfo::where('imei_1', $imei)->update([
                    'retail_out' => $now,
                    'retail_state' => 2,
                ]);
            }

            DB::commit();
            return back()->with('success', 'Device Sell Successful');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function apiSellDevice(Request $request)
    {
        // Log::info('API Sell Device Request: ', $request->all());
        if ($request->header('Authorization') !== 'iF3PTw5zRS7JdKeu2ULE3A==') {
            return response()->json([
                'status' => 400,
                'message' => 'Unauthorized access',
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'data' => 'required|array|min:1',
            'data.*.imei_1' => 'required|string|distinct',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $responses = [];
        $now = Carbon::now();

        DB::beginTransaction();
        try {
            foreach ($validated['data'] as $item) {
                $imei = $item['imei_1'];

                $distributorIn = DistributorIn::where('imei_1', $imei)->first();
                if (!$distributorIn) {
                    $responses[] = [
                        'imei_1' => $imei,
                        'status' => 404,
                        'message' => 'IMEI not found in stock'
                    ];
                    continue;
                }

                if ($distributorIn->retail_status == 0) {
                    $responses[] = [
                        'imei_1' => $imei,
                        'status' => 422,
                        'message' => 'Device already sold'
                    ];
                    continue;
                }

                if ($distributorIn->retail_status != 1) {
                    $responses[] = [
                        'imei_1' => $imei,
                        'status' => 422,
                        'message' => 'Device not in the stock'
                    ];
                    continue;
                }

                DistributorIn::where('imei_1', $imei)->update([
                    'retail_status' => 0,
                    'retail_out' => $now,
                ]);

                ImeiInfo::where('imei_1', $imei)->update([
                    'retail_out' => $now,
                    'retail_state' => 2,
                ]);

                $responses[] = [
                    'imei_1' => $imei,
                    'status' => 200,
                    'message' => 'Device Sell Successful'
                ];
            }

            DB::commit();

            return response()->json([
                'status' => 200,
                'results' => $responses
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'message' => 'Transaction failed: ' . $e->getMessage()
            ], 500);
        }
    }
    public function stockReport()
    {
        $distributor= Distributor::all();
        $product = Product::all();
        $variant = Variant::all();
        $model = DeviceModel::all();
        return view('retail.report' , compact('distributor', 'product', 'variant', 'model'));
    }

    public function ReportData(Request $request)
    {
        // Base query
        $baseQuery = DB::table('distributor_in')
            ->whereIn('retail_status', [0, 1])
            ->whereNotNull('retail_id')
            ->where('retail_id', '!=', '');

        if ($request->filled('distributor_id')) {
            $baseQuery->where('distributor_id', $request->distributor_id);
        }

        if ($request->filled('retail_id')) {
            $baseQuery->where('retail_id', $request->retail_id);
        }

        $recordsTotal = (clone $baseQuery)->count();

        $filteredQuery = clone $baseQuery;

        $filteredQuery->when($request->filled('startdate'), function ($q) use ($request) {
            $q->whereDate('created_at', '>=', $request->startdate);
        });

        $filteredQuery->when($request->filled('enddate'), function ($q) use ($request) {
            $q->whereDate('created_at', '<=', $request->enddate);
        });

        $filteredQuery->when($request->filled('product_name'), function ($q) use ($request) {
            $q->where('product_name', $request->product_name);
        });

        $filteredQuery->when($request->filled('model'), function ($q) use ($request) {
            $q->where('model', $request->model);
        });

        $filteredQuery->when($request->filled('variant_name'), function ($q) use ($request) {
            $q->where('variant', $request->variant_name);
        });

        $filteredQuery->when($request->filled('status'), function ($q) use ($request) {
            $q->where('retail_status', $request->status);
        });

        $recordsFiltered = (clone $filteredQuery)->count();

        $query = $filteredQuery->select(
            'product_name',
            'brand',
            'manufacturer',
            'model',
            'variant as variant_name',
            DB::raw('COUNT(*) as quantity')
        )->groupBy('product_name', 'model', 'variant');

        if ($request->has('start') && $request->has('length')) {
            $query->skip($request->start)->take($request->length);
        }

        if ($request->has('order')) {
            $columnIndex = $request->order[0]['column'];
            $columnName = $request->columns[$columnIndex]['data'];
            $direction = $request->order[0]['dir'];

            if (in_array($columnName, ['product_name', 'brand', 'manufacturer', 'model', 'variant_name', 'quantity'])) {
                $query->orderBy($columnName, $direction);
            }
        }

        $data = $query->get();

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function getRetailsByDistributor($id)
    {
        $retails = Retail::where('distributor_id', $id)->get(['id', 'retail_name']);
        return response()->json($retails);
    }
}
