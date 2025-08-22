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





class PricingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function show(Request $request)
    {
        $columns = [
            0 => 'id',
            1 => 'product_id',
            2 => 'product_name',
            3 => 'variant_name',
            4 => 'landed_cost',
            5 => 'dealer_cost',
            6 => 'vat_tax',
            7 => 'model',
            8 => 'manufacturer',
            9 => 'brand',
            10 => 'created_by',
            11 => 'updated_by',
        ];
    
        $totalData = Pricing::count();
        $totalFiltered = $totalData;
    
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';
        $orderDir = $request->input('order.0.dir', 'asc');
    
        $query = Pricing::with(['creator', 'updater']);
    
        // Search
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('variant_name', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('manufacturer', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
            $totalFiltered = $query->count();
        }
    
        // Ordering
        $query->orderBy($orderColumn, $orderDir);
    
        // Pagination
        $pricings = $query->offset($start)->limit($limit)->get();
    
    
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
                <a href="'.route('pricing.edit', $pricing->id).'" class="btn btn-sm btn-light" title="Edit">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="'.route('pricing.delete', $pricing->id).'" method="POST" style="display:inline;">
                    '.csrf_field().method_field('DELETE').'
                    <button onclick="return confirm(\'Delete this pricing?\')" class="btn btn-sm btn-light" title="Delete">
                        <i class="fas fa-trash-alt text-danger"></i>
                    </button>
                </form>'
            ];
        }
    
    
        $json_data = [
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        ];
    
        return response()->json($json_data);
    }
    





    public function index()
    {
        $pricings = Pricing::with(['creator', 'updater'])->get();
        return view('pricing.index', compact('pricings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

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
        'product_id'    => 'required|integer',
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

    return redirect()->route('pricing.index')->with('success', 'Pricing created successfully!');
}



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $products = Product::all();
        $pricing = Pricing::findOrFail($id);
        $variants = 'Yellow';
        return view('pricing.edit',compact('pricing','products','variants'));
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
        'product_id'    => 'required|integer',
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
        'product_id'    => $product->id,
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

    return redirect()->route('pricing.index')->with('success', 'Pricing updated successfully!');
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
        return redirect()->route('pricing.index')->with('success','Pricing deleted successfully');

    }
}
