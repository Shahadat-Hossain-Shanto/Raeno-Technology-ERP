<?php

namespace App\Http\Controllers;

use App\Models\AreaUser;
use App\Models\DeviceModel;
use App\Models\DistributorIn;
use Illuminate\Http\Request;

use App\Models\PrimaryStockIn;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Variant;
use App\Models\TerritoryUser;
use App\Models\Distributor;
use App\Models\RegionUser;

// use DB;

class PrimaryInReportController extends Controller
{
    public function index()
    {

        $product = Product::all();
        $variant = Variant::all();
        $model = DeviceModel::all();
        return view('report/primary-in-report', compact('product', 'variant', 'model'));
    }

    public function onLoad(Request $request)
    {
        // Total count of only status = 1 records
        $recordsTotal = PrimaryStockIn::where('status', 1)->count();

        $query = PrimaryStockIn::with('creator')->where('status', 1); // apply status=1 globally

        if (!empty($request->product_name)) {
            $query->where('product_name', $request->product_name);
        }
        if (!empty($request->model)) {
            $query->where('model', $request->model);
        }
        if (!empty($request->variant_name)) {
            $query->where('variant', $request->variant_name);
        }

        // Get filtered count before grouping
        $recordsFiltered = (clone $query)->count();

        if ($request->has('order.0.column')) {
            $columnIndex = $request->order[0]['column'];
            $direction = $request->order[0]['dir'] ?? 'asc';

            $columns = [
                1 => 'product_name',
                2 => 'brand',
                3 => 'manufacturer',
                4 => 'model',
                5 => 'variant',
                6 => 'created_by',
            ];

            $columnName = $columns[$columnIndex] ?? 'created_at';
            $query->orderBy($columnName, $direction);
        }

        $start = intval($request->start ?? 0);
        $length = intval($request->length ?? 10);

        // Fetch all filtered data (status=1) and then group
        $items = $query->get();

        $grouped = $items->groupBy(function ($item) {
            return $item->product_name . '||' . $item->model . '||' . $item->variant;
        })->map(function ($group) {
            $first = $group->first();
            return [
                'product_name'     => $first->product_name,
                'brand'            => $first->brand,
                'manufacturer'     => $first->manufacturer,
                'model'            => $first->model,
                'variant_name'     => $first->variant,
                'price'            => $first->price,
                'quantity'         => $group->count(),
                'created_by_name'  => optional($first->creator)->name ?? 'N/A',
            ];
        })->values();

        // Manual pagination
        $data = $grouped->slice($start, $length)->values();

        return response()->json([
            'draw'            => intval($request->draw),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ]);
    }


    public function primaryStock()
    {

        $product = Product::all();
        $variant = Variant::all();
        $model = DeviceModel::all();
        return view('report/primary-stock-report', compact('product', 'variant', 'model'));
    }

    public function primaryStockData(Request $request)
    {
        $recordsTotal = DB::table('primary_stock_in')->count();

        $filteredQuery = DB::table('primary_stock_in')
            ->where(function ($q) use ($request) {
                if ($request->filled('startdate')) {
                    $q->whereDate('created_at', '>=', $request->startdate);
                }

                if ($request->filled('enddate')) {
                    $q->whereDate('created_at', '<=', $request->enddate);
                }

                if ($request->filled('product_name')) {
                    $q->where('product_name', $request->product_name);
                }

                if ($request->filled('model')) {
                    $q->where('model', $request->model);
                }

                if ($request->filled('variant_name')) {
                    $q->where('variant', $request->variant_name);
                }

                if ($request->filled('status')) {
                    $q->where('status', $request->status);
                }
            });

        $recordsFiltered = (clone $filteredQuery)->count();

        $query = $filteredQuery->select(
            'product_name',
            'brand',
            'manufacturer',
            'model',
            'variant as variant_name',
            DB::raw('COUNT(*) as quantity')
        )
        ->groupBy('product_name', 'model', 'variant');

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

    public function disIndex()
    {

        $product = Product::all();
        $variant = Variant::all();
        $model = DeviceModel::all();
        return view('report/distributor-in-report', compact('product', 'variant', 'model'));
    }

    public function distributor(Request $request)
    {
        $user = auth()->user();

        // Base query
        $query = DistributorIn::with('receivedByUser')->where('status', 1);

        // If user has distributor_id, apply filter
        if (!empty($user->distributor)) {
            $query->where('distributor_id', $user->distributor);
        }

        $recordsTotal = (clone $query)->count();

        // Apply filters from request
        if (!empty($request->product_name)) {
            $query->where('product_name', $request->product_name);
        }
        if (!empty($request->model)) {
            $query->where('model', $request->model);
        }
        if (!empty($request->variant_name)) {
            $query->where('variant', $request->variant_name);
        }

        $recordsFiltered = (clone $query)->count();

        // Apply ordering if requested
        if ($request->has('order.0.column')) {
            $columnIndex = $request->order[0]['column'];
            $direction = $request->order[0]['dir'] ?? 'asc';

            $columns = [
                1 => 'product_name',
                2 => 'brand',
                3 => 'manufacturer',
                4 => 'model',
                5 => 'variant',
                6 => 'received_by',
            ];

            $columnName = $columns[$columnIndex] ?? 'created_at';
            $query->orderBy($columnName, $direction);
        }

        $start = intval($request->start ?? 0);
        $length = intval($request->length ?? 10);

        $items = $query->get();

        $grouped = $items->groupBy(function ($item) {
            return $item->product_name . '||' . $item->model . '||' . $item->variant;
        })->map(function ($group) {
            $first = $group->first();
            return [
                'product_name'     => $first->product_name,
                'brand'            => $first->brand,
                'manufacturer'     => $first->manufacturer,
                'model'            => $first->model,
                'variant_name'     => $first->variant,
                'quantity'         => $group->count(),
                'received_by_name' => optional($first->receivedByUser)->name ?? 'N/A',
            ];
        })->values();

        $data = $grouped->slice($start, $length)->values();

        return response()->json([
            'draw'            => intval($request->draw),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ]);
    }


    public function tsIndex()
    {
        $user = Auth::user();

        // Default: get all distributors
        $distributorQuery = Distributor::query();

        if ($user->hasRole('Distributor')) {
            $distributorQuery->where('id', $user->distributor);
        } elseif ($user->hasRole('TSM')) {
            $territoryIds = TerritoryUser::where('user_id', $user->id)->pluck('territory_id');
            $distributorQuery->whereIn('territory_id', $territoryIds);
        } elseif ($user->hasRole('ASM')) {
            $areaIds = AreaUser::where('user_id', $user->id)->pluck('area_id');
            $distributorQuery->whereIn('area_id', $areaIds);
        } elseif ($user->hasRole('RSM')) {
            $regionIds = RegionUser::where('user_id', $user->id)->pluck('region_id');
            $distributorQuery->whereIn('region_id', $regionIds);
        }

        $distributor = $distributorQuery->get();
        $product = Product::all();
        $variant = Variant::all();
        $model = DeviceModel::all();

        return view('report/secondary-in-report', compact('product', 'variant', 'model', 'distributor'));
    }

    public function getReportData(Request $request)
    {
        $user = Auth::user();

        // Common Distributor ID Filtering Logic
        $distributorIds = [];

        if ($user->hasRole('TSM')) {
            $territoryIds = TerritoryUser::where('user_id', $user->id)->pluck('territory_id');
            $distributorIds = Distributor::whereIn('territory_id', $territoryIds)->pluck('id');
        } elseif ($user->hasRole('ASM')) {
            $areaIds = AreaUser::where('user_id', $user->id)->pluck('area_id');
            $distributorIds = Distributor::whereIn('area_id', $areaIds)->pluck('id');
        } elseif ($user->hasRole('RSM')) {
            $regionIds = RegionUser::where('user_id', $user->id)->pluck('region_id');
            $distributorIds = Distributor::whereIn('region_id', $regionIds)->pluck('id');
        } elseif ($user->distributor) {
            $distributorIds = collect([$user->distributor]);
        }

        // Base query with status = 1 only (stocked data)
        $baseQuery = DB::table('distributor_in')->where('status', 1);

        // Apply distributor filter
        if (!empty($distributorIds)) {
            $baseQuery->whereIn('distributor_id', $distributorIds);
        }

        $recordsTotal = (clone $baseQuery)->count();

        // Filtered query with distributor condition included
        $filteredQuery = clone $baseQuery;

        $filteredQuery->where(function ($q) use ($request) {
            if ($request->filled('startdate')) {
                $q->whereDate('created_at', '>=', $request->startdate);
            }

            if ($request->filled('enddate')) {
                $q->whereDate('created_at', '<=', $request->enddate);
            }

            if ($request->filled('product_name')) {
                $q->where('product_name', $request->product_name);
            }

            if ($request->filled('model')) {
                $q->where('model', $request->model);
            }

            if ($request->filled('variant_name')) {
                $q->where('variant', $request->variant_name);
            }

            // Apply requested distributor filter (in addition to role-based)
            if ($request->filled('distributor_id')) {
                $q->where('distributor_id', $request->distributor_id);
            }
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

        // Pagination
        if ($request->has('start') && $request->has('length')) {
            $query->skip($request->start)->take($request->length);
        }

        // Ordering
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


    // public function getUserTerritoryDistributors()
    // {
    // $user = auth()->user();

    // $territoryIds = TerritoryUser::where('user_id', $user->id)->pluck('territory_id');

    // $distributors = Distributor::whereIn('territory_id', $territoryIds)
    //     ->select('id', 'distributor_name')
    //     ->orderBy('distributor_name')
    //     ->get();

    // return response()->json(['distributors' => $distributors]);
    // }


    public function reports(Request $request)
    {

        // Log::info($request->startdate);
        // Log::info($request->enddate);
        // Log::info($request->store);

        $authId = Auth::user()->subscriber_id;
        $from = date($request->startdate);
        $to = date($request->enddate);

        if ($request->filled('startdate') && $request->filled('enddate') && ($request->input('store') == 'all_store')) {

            $data = DB::table("product_in_histories")
                ->select(DB::raw("SUM(quantity) as qty"), "product_name", "store_name", "product", "store", "variant_name", "variant_id")
                // ->whereBetween('created_at', [$from, $to])
                ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->where('subscriber_id', $authId)
                ->groupBy("product_name", "store_name", "product", "store", "variant_name")
                ->orderBy("store_name", "desc")
                ->orderBy("product_name", "desc")
                ->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'No data!',
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'Success',
                    'data' => $data
                ]);
            }
        } elseif ($request->filled('startdate') && $request->filled('enddate') && ($request->input('store') == 'warehouse')) {
            $data = DB::table("product_in_histories")
                ->select(DB::raw("SUM(quantity) as qty"), "product_name", "store_name", "product", "store", "variant_name", "variant_id")
                ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->where([
                    ['subscriber_id', $authId],
                    ['store', '=', 0],
                ])
                ->groupBy("product_name", "store_name", "product", "store", "variant_name")
                ->orderBy("store_name", "desc")
                ->orderBy("product_name", "desc")
                ->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'No data!',
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'Success',
                    'data' => $data
                ]);
            }
        } elseif ($request->input('startdate') == NULL && $request->input('enddate') == NULL && ($request->input('store') == 'all_store')) {
            $data = DB::table("product_in_histories")
                ->select(DB::raw("SUM(quantity) as qty"), "product_name", "store_name", "product", "store", "variant_name", "variant_id")
                // ->whereBetween('created_at', [$from, $to])
                ->where('subscriber_id', $authId)
                ->groupBy("product_name", "store_name", "product", "store", "variant_name")
                ->orderBy("store_name", "desc")
                ->orderBy("product_name", "desc")
                ->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'No data!',
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'Success',
                    'data' => $data
                ]);
            }
        } elseif ($request->input('startdate') == NULL && $request->input('enddate') == NULL && ($request->input('store') == 'warehouse')) {
            $data = DB::table("product_in_histories")
                ->select(DB::raw("SUM(quantity) as qty"), "product_name", "store_name", "product", "store", "variant_name", "variant_id")
                // ->whereBetween('created_at', [$from, $to])
                ->where([
                    ['subscriber_id', $authId],
                    ['store', '=', 0],
                ])
                ->groupBy("product_name", "store_name", "product", "store", "variant_name")
                ->orderBy("store_name", "desc")
                ->orderBy("product_name", "desc")
                ->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'No data!',
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'Success',
                    'data' => $data
                ]);
            }
        } elseif ($request->filled('startdate') && ($request->input('store') == 'warehouse') && ($request->input('enddate') == NULL)) {
            $data = DB::table("product_in_histories")
                ->select(DB::raw("SUM(quantity) as qty"), "product_name", "store_name", "product", "store", "variant_name", "variant_id")
                ->whereDate('created_at', '=', $from)
                ->where([
                    ['subscriber_id', $authId],
                    ['store', '=', 0],
                ])
                ->groupBy("product_name", "store_name", "product", "store", "variant_name")
                ->orderBy("store_name", "desc")
                ->orderBy("product_name", "desc")
                ->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'No data!',
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'Success',
                    'data' => $data
                ]);
            }
        } elseif ($request->filled('enddate') && ($request->input('store') == 'warehouse') && ($request->input('startdate') == NULL)) {
            $data = DB::table("product_in_histories")
                ->select(DB::raw("SUM(quantity) as qty"), "product_name", "store_name", "product", "store", "variant_name", "variant_id")
                ->whereDate('created_at', '=', $to)
                ->where([
                    ['subscriber_id', $authId],
                    ['store', '=', 0],
                ])
                ->groupBy("product_name", "store_name", "product", "store", "variant_name")
                ->orderBy("store_name", "desc")
                ->orderBy("product_name", "desc")
                ->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'No data!',
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'Success',
                    'data' => $data
                ]);
            }
        } elseif ($request->filled('startdate') && ($request->input('store') == 'all_store') && ($request->input('enddate') == NULL)) {
            $data = DB::table("product_in_histories")
                ->select(DB::raw("SUM(quantity) as qty"), "product_name", "store_name", "product", "store", "variant_name", "variant_id")
                ->whereDate('created_at', '=', $from)
                ->where('subscriber_id',  $authId)
                ->groupBy("product_name", "store_name", "product", "store", "variant_name")
                ->orderBy("store_name", "desc")
                ->orderBy("product_name", "desc")
                ->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'No data!',
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'Success',
                    'data' => $data
                ]);
            }
        } elseif ($request->filled('enddate') && ($request->input('store') == 'all_store') && ($request->input('startdate') == NULL)) {
            $data = DB::table("product_in_histories")
                ->select(DB::raw("SUM(quantity) as qty"), "product_name", "store_name", "product", "store", "variant_name", "variant_id")
                ->whereDate('created_at', '=', $to)
                ->where('subscriber_id',  $authId)
                ->groupBy("product_name", "store_name", "product", "store", "variant_name")
                ->orderBy("store_name", "desc")
                ->orderBy("product_name", "desc")
                ->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'No data!',
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'Success',
                    'data' => $data
                ]);
            }
        } elseif ($request->filled('store') && $request->filled('enddate') && $request->filled('startdate')) {

            $data = DB::table("product_in_histories")
                ->select(DB::raw("SUM(quantity) as qty"), "product_name", "store_name", "product", "store", "variant_name", "variant_id")
                ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->where([
                    ['store', '=', $request->store],
                    ['subscriber_id', '=', $authId]
                ])
                ->groupBy("product_name", "store_name", "product", "store", "variant_name")
                ->orderBy("store_name", "desc")
                ->orderBy("product_name", "desc")
                ->get();

            Log::info($data);

            if ($data->isEmpty()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'No data!',
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'Success',
                    'data' => $data
                ]);
            }
        } elseif ($request->filled('store') && $request->filled('startdate') && ($request->input('enddate') == NULL)) {
            $data = DB::table("product_in_histories")
                ->select(DB::raw("SUM(quantity) as qty"), "product_name", "store_name", "product", "store", "variant_name", "variant_id")
                ->whereDate('created_at', '=', $from)
                ->where([
                    ['store', '=', $request->store],
                    ['subscriber_id', '=', $authId]
                ])
                ->groupBy("product_name", "store_name", "product", "store", "variant_name")
                ->orderBy("store_name", "desc")
                ->orderBy("product_name", "desc")
                ->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'No data!',
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'Success',
                    'data' => $data
                ]);
            }
        } elseif ($request->filled('store') && $request->filled('enddate') && ($request->input('startdate') == NULL)) {
            $data = DB::table("product_in_histories")
                ->select(DB::raw("SUM(quantity) as qty"), "product_name", "store_name", "product", "store", "variant_name", "variant_id")
                ->whereDate('created_at', '=', $to)
                ->where([
                    ['store', '=', $request->store],
                    ['subscriber_id', '=', $authId]
                ])
                ->groupBy("product_name", "store_name", "product", "store", "variant_name")
                ->orderBy("store_name", "desc")
                ->orderBy("product_name", "desc")
                ->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'No data!',
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'Success',
                    'data' => $data
                ]);
            }
        } elseif ($request->filled('store') && ($request->input('enddate') == NULL) && ($request->input('startdate') == NULL)) {
            $data = DB::table("product_in_histories")
                ->select(DB::raw("SUM(quantity) as qty"), "product_name", "store_name", "product", "store", "variant_name", "variant_id")
                // ->whereDate('created_at', '=', $to)
                ->where([
                    ['store', '=', $request->store],
                    ['subscriber_id', '=', $authId]
                ])
                ->groupBy("product_name", "store_name", "product", "store", "variant_name")
                ->orderBy("store_name", "desc")
                ->orderBy("product_name", "desc")
                ->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'No data!',
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'Success',
                    'data' => $data
                ]);
            }
        }


        // Log::info($data);


    }
}
