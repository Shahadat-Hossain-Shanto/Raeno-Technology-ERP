<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pricing;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Brand;
use App\Models\Models;
use App\Models\Manufacturer;
use App\Models\DeviceModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class PricingListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        return view('pricing_list.index');
    }


    public function getData(Request $request)
    {
        $columns = [
            0 => 'id',
            1 => 'product_name',
            2 => 'variant_name',
            3 => 'landed_cost',
            4 => 'dealer_cost',
            5 => 'vat_tax',
            6 => 'model',
            7 => 'manufacturer',
            8 => 'brand',
            9 => 'created_by',
            10 => 'updated_by',
        ];
    
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';
        $orderDir = $request->input('order.0.dir', 'asc');
        $search = $request->input('search.value');
    
        // Step 1: Subquery to get latest IDs per product_id + variant_id
        $latestIds = Pricing::select(DB::raw('MAX(id) as id'))
            ->groupBy('product_id', 'variant_id');
    
        // Step 2: Main query
        $query = Pricing::with(['creator', 'updater'])
            ->whereIn('id', $latestIds->pluck('id'));
    
        // Step 3: Filter search
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('variant_name', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('manufacturer', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }
    
        $totalFiltered = $query->count();
        $totalData = Pricing::whereIn('id', $latestIds->pluck('id'))->count();
    
        $pricings = $query->orderBy($orderColumn, $orderDir)
                        ->offset($start)
                        ->limit($limit)
                        ->get();
    
        $data = [];
        $serial = $start + 1;
    
        foreach ($pricings as $pricing) {
            $data[] = [
                'sl' => $serial++,
                'product_name' => $pricing->product_name,
                'variant_name' => $pricing->variant_name,
                'landed_cost' => $pricing->landed_cost,
                'dealer_cost' => $pricing->dealer_cost,
                'vat_tax' => $pricing->vat_tax,
                'model' => $pricing->model,
                'manufacturer' => $pricing->manufacturer,
                'brand' => $pricing->brand,
                'creator_name' => $pricing->creator->name ?? 'N/A',
                'updater_name' => $pricing->updater->name ?? 'N/A',
                'actions' => '
                    <a href="'.route('pricing-list.edit', $pricing->id).'" class="btn btn-sm btn-primary" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="'.route('pricing-list.delete', $pricing->id).'" method="POST" style="display:inline;">
                        '.csrf_field().method_field('DELETE').'
                        <button onclick="return confirm(\'Delete this pricing?\')" class="btn btn-sm btn-danger" title="Delete">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>'
            ];
        }
    
        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $totalData,
            "recordsFiltered" => $totalFiltered,
            "data" => $data
        ]);
    }
    

    
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    $products = Product::all();
    $variants = [];         // from variants table
    $models = Models::all();       // model list
    $manufacturers = Manufacturer::all();
    $brands = Brand::all();
    Log::info($request->product_id);

    return view('pricing_list.create', compact('products','variants','brands','models','manufacturers'));
   }

    public function getVariants($product_id)
    {
        $variants = Variant::where('product_id', $product_id)->get();

        return response()->json($variants);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function store(Request $request)
     {
         Log::info($request);
         $request->validate([
             'variant'       => 'nullable|string|max:255',
             'landed_cost'   => 'nullable|numeric',
             'dealer_cost'   => 'nullable|numeric',
             'vat_tax'       => 'nullable|boolean',
         ]);
     
         $product = Product::findOrFail($request->product_id);
         $variant= Variant::findOrFail($request->variant_id);
         // $subscriberId = $product->subscriber_id;
     
         // $model = DeviceModel::where('subscriber_id', $subscriberId)->first();
         // $manufacturer = Manufacturer::where('subscriber_id', $subscriberId)->first();
     
         Log::info($product->model ?? null);
     
         Pricing::create([
             'product_id'    => $product->id,
             'product_name'  => $product->productName,
             'variant_id'    => $variant->id,
             'variant_name'  => $variant->variant_name,
             'landed_cost'   => $request->landed_cost,
             'dealer_cost'   => $request->dealer_cost,
             'vat_tax'       => $request->vat_tax ?? 1,
             'model'         => $product->model ?? null,
             'manufacturer'  => $product->manufacturer ?? null,
             'brand'         => $product->brand ?? null,
             'created_by'    => Auth::id(),
             'updated_by'    => Auth::id(),
         ]);
     
         return redirect()->route('pricing-list.index')->with('success', 'Pricing created successfully!');
     }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function edit($id)
   {
       $pricing = Pricing::findOrFail($id);
   
       $products = Product::all(); // Or wherever you're getting products from
   
       return view('pricing_list.edit', compact('pricing', 'products'));
   }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming data
        $request->validate([
           
            'variant'       => 'nullable|string|max:255',
            'landed_cost'   => 'nullable|numeric',
            'dealer_cost'   => 'nullable|numeric',
            'vat_tax'       => 'nullable|boolean',
        ]);
    
        // Find the related product and pricing record
        $product = Product::findOrFail($request->product_id);
        $pricing = Pricing::findOrFail($id);
    
        // Update the pricing record
        $pricing->update([         
            'product_name'  => $product->productName,
            'variant_name'  => $request->variant,
            'landed_cost'   => $request->landed_cost,
            'dealer_cost'   => $request->dealer_cost,
            'vat_tax'       => $request->vat_tax ?? 1,
            'model'         => $product->model ?? null,
            'manufacturer'  => $product->manufacturer ?? null,
            'brand'         => $product->brand ?? null,
            'updated_by'    => Auth::id(),
        ]);
    
        return redirect()->route('pricing-list.index')->with('success', 'Pricing updated successfully!');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function destroy($id)
    {
        $pricing = Pricing::findOrFail($id);
        $pricing->delete();
        return redirect()->route('pricing-list.index')->with('success','Pricing deleted successfully');

    }
}
