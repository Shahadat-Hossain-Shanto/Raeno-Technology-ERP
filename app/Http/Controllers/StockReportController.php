<?php

namespace App\Http\Controllers;

use App\Models\AreaUser;
use App\Models\Store;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\Variant;
use App\Models\DeviceModel;
use Illuminate\Support\Facades\DB;
use App\Models\ProductSerial;
use Illuminate\Support\Facades\Auth;
use App\Models\TerritoryUser;
use App\Models\Distributor;
use App\Models\RegionUser;

class StockReportController extends Controller
{
    public function index(){

        $stores = Store::where('subscriber_id', Auth::user()->subscriber_id)->get();
        return view('report/stock-report', ['stores' => $stores]);
    }

    public function inventoryStockData(Request $request){

        $data = Product::join('inventories', 'products.id', 'inventories.productId')
                ->where('products.subscriber_id', Auth::user()->subscriber_id)
                ->get();

        return response()->json([
            'data' => $data,
            'message' => 'Success'
        ]);
    }

    public function getSerial($id){

        $inventory = Inventory::find($id);

        $serial = ProductSerial::join('products', 'products.id', 'product_serials.productId')->where([
            ['product_serials.productId', $inventory->productId],
            ['product_serials.variantId', $inventory->variant_id],
            ['product_serials.storeId', 0],
            ['product_serials.saleId', 0],
            ['product_serials.subscriber_id', Auth::user()->subscriber_id],
        ])
        ->select('product_serials.productName','products.type', 'product_serials.variantName', 'product_serials.serialNumber')->get();

        return response()->json([
            'data' => $serial,
            'message' => 'Success'
        ]);
    }

    public function distributorStock()
    {

        $product = Product::all();
        $variant = Variant::all();
        $model = DeviceModel::all();
        return view('report/distributor-stock-report', compact('product', 'variant', 'model'));
    }


    public function distributorStockData(Request $request)
    {
        $user = Auth::user();

        $baseQuery = DB::table('distributor_in')->whereIn('status', [0, 1]);

        if ($user->distributor) {
            $baseQuery->where('distributor_id', $user->distributor);
        }

        $recordsTotal = (clone $baseQuery)->count();

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

    public function secondaryDistributorStock()
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

        return view('report.secondary-distributor-stock-report', compact('product', 'variant', 'model', 'distributor'));
    }

    public function secondaryDistributorStockData(Request $request)
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

        // Base query
        $baseQuery = DB::table('distributor_in')->whereIn('status', [0, 1]);

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

            if ($request->filled('status')) {
                $q->where('status', $request->status);
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

    public function getUserTerritoryDistributors()
    {
        $user = auth()->user();

        $territoryIds = TerritoryUser::where('user_id', $user->id)->pluck('territory_id');

        $distributors = Distributor::whereIn('territory_id', $territoryIds)
            ->select('id', 'distributor_name')
            ->orderBy('distributor_name')
            ->get();

        return response()->json(['distributors' => $distributors]);
    }

}
