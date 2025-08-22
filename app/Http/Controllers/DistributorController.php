<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use App\Models\Distributor;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\District;
use App\Models\ChartOfAccount;
use App\Models\Region;
use App\Models\Territory;

class DistributorController extends Controller
{
    public function index()
    {
        return view('distributor.index');
    }

   public function getData(Request $request)
   {
       $columns = [
           0  => 'id',
           1  => 'distributor_name',
           2  => 'owner_name',
           3  => 'nid',
           4  => 'contact_no',
           5  => 'email',
           6  => 'address',
           7  => 'business_type',
           8  => 'districts.name',
           9  => 'territories.territory_name',
           10 => 'trade_license_no',
           11 => 'trade_license_validity',
           12 => 'tin',
           13 => 'bank_name',
           14 => 'branch',
           15 => 'account_name',
           16 => 'account_no',
           17 => 'existing_distributor_brands',
       ];

       $totalData = Distributor::count();
       $totalFiltered = $totalData;

       $limit = $request->input('length', 10);
       $start = $request->input('start', 0);
       $orderColumnIndex = $request->input('order.0.column', 0);
       $orderColumn = $columns[$orderColumnIndex] ?? 'id';
       $orderDir = $request->input('order.0.dir', 'asc');

       $query = Distributor::with(['district', 'territory']);

       // Search
       if ($search = $request->input('search.value')) {
           $query->where(function ($q) use ($search) {
               $q->where('distributor_name', 'like', "%{$search}%")
                 ->orWhere('owner_name', 'like', "%{$search}%")
                 ->orWhere('nid', 'like', "%{$search}%")
                 ->orWhere('contact_no', 'like', "%{$search}%")
                 ->orWhere('email', 'like', "%{$search}%")
                 ->orWhere('address', 'like', "%{$search}%")
                 ->orWhere('business_type', 'like', "%{$search}%")
                 ->orWhere('trade_license_no', 'like', "%{$search}%")
                 ->orWhere('tin', 'like', "%{$search}%")
                 ->orWhere('bank_name', 'like', "%{$search}%")
                 ->orWhere('branch', 'like', "%{$search}%")
                 ->orWhere('account_name', 'like', "%{$search}%")
                 ->orWhere('account_no', 'like', "%{$search}%")
                 ->orWhere('existing_distributor_brands', 'like', "%{$search}%")
                 ->orWhereHas('district', function ($sub) use ($search) {
                     $sub->where('name', 'like', "%{$search}%");
                 })
                 ->orWhereHas('territory', function ($sub) use ($search) {
                     $sub->where('territory_name', 'like', "%{$search}%");
                 });
           });

           $totalFiltered = $query->count();
       }

       $distributors = $query->orderBy($orderColumn, $orderDir)
           ->offset($start)
           ->limit($limit)
           ->get();

       $data = [];
       foreach ($distributors as $dist) {
           $data[] = [
               'id' => $dist->id,
               'distributor_name' => $dist->distributor_name,
               'owner_name' => $dist->owner_name,
               'nid' => $dist->nid,
               'contact_no' => $dist->contact_no,
               'email' => $dist->email,
               'address' => $dist->address,
               'business_type' => $dist->business_type,
               'district_id' => $dist->district->name ?? 'N/A',
               'territory_id' => $dist->territory->territory_name ?? 'N/A',
               'trade_license_no' => $dist->trade_license_no,
               'trade_license_validity' => $dist->trade_license_validity,
               'tin' => $dist->tin,
               'bank_name' => $dist->bank_name,
               'branch' => $dist->branch,
               'account_name' => $dist->account_name,
               'account_no' => $dist->account_no,
               'existing_distributor_brands' => $dist->existing_distributor_brands,
               'actions' => '
                   <a href="'.route('distributor.edit', $dist->id).'" class="btn btn-sm btn-light" title="Edit">
                       <i class="fas fa-edit"></i>
                   </a>
                   <form action="'.route('distributor.destroy', $dist->id).'" method="POST" style="display:inline;">
                       '.csrf_field().method_field('DELETE').'
                       <button onclick="return confirm(\'Delete this distributor?\')" class="btn btn-sm btn-light" title="Delete">
                           <i class="fas fa-trash-alt text-danger"></i>
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


    public function create()
    {
        $districts = District::all();
        $regions = Region::all();
        $areas = Area::all();
        $territories = Territory::all();
        return view('distributor.create' , compact('districts','regions','areas','territories'));
    }

    public function getRegionIdByName(Request $request)
    {
        $region = Region::where('region_name', $request->region_name)->first();
        return response()->json(['region_id' => $region?->id]);
    }

    public function getTerritoriesByAreaName(Request $request)
    {
        $areaName = $request->input('area_name');
        $territories = Territory::where('area_name', $areaName)->get();
        return response()->json($territories);
    }






    public function store(Request $request)
    {
        Log::info($request->all());
        $request->validate([
            'distributor_name'            => 'required|string|max:255',
            'owner_name'                  => 'required|string|max:255',
            'nid'                         => 'nullable|string|max:50',
            'contact_no'                  => 'required|string|max:20',
            'email'                       => 'nullable|email|max:255',
            'address'                     => 'nullable|string|max:500',
            'business_type'                 => 'required|string|max:100',
            'district_id'                 => 'nullable|max:10',
            'trade_license_no'           => 'nullable|string|max:100',
            'trade_license_validity'     => 'nullable|date',
            'tin'                         => 'nullable|string|max:100',
            'bank_name'                   => 'nullable|string|max:100',
            'branch'                      => 'nullable|string|max:100',
            'account_name'                => 'nullable|string|max:255',
            'account_no'                  => 'nullable|string|max:100',
            'credit_limit'               => 'nullable|numeric|min:0',
            'existing_distributor_brands'=> 'nullable|string|max:255',
        ]);

        // Get the parent 'Distributor Receivable' head info dynamically
        $distributorReceivableParent = ChartOfAccount::where([
            ['head_name', 'Distributor Receivable'],
        ])->first();

        if (!$distributorReceivableParent) {
            return back()->with('error', 'Distributor Receivable head is missing in Chart of Accounts.');
        }

        // Find last child to calculate new head_code
        $lastChild = ChartOfAccount::where([
            ['parent_head_level', $distributorReceivableParent->head_code]
        ])->latest()->first();

        $newHeadCode = $lastChild
            ? $lastChild->head_code + 1
            : ($distributorReceivableParent->head_code * 100) + 1;

        // Create new COA entry under that parent
        $coa = new ChartOfAccount;
        $coa->head_code         = $newHeadCode;
        $coa->head_name         = $request->distributor_name.' '.$request->contact_no;
        $coa->parent_head       = $distributorReceivableParent->head_name;
        $coa->parent_head_level = $distributorReceivableParent->head_code;
        $coa->head_type         = $distributorReceivableParent->head_type;
        $coa->is_transaction    = $distributorReceivableParent->is_transaction;
        $coa->is_active         = $distributorReceivableParent->is_active;
        $coa->is_general_ledger = $distributorReceivableParent->is_general_ledger;
        $coa->subscriber_id     = Auth::user()->subscriber_id;
        $coa->save();

          Distributor::create([
            'distributor_name'            => $request->distributor_name,
            'owner_name'                  => $request->owner_name,
            'nid'                         => $request->nid,
            'contact_no'                  => $request->contact_no,
            'email'                       => $request->email,
            'address'                     => $request->address,
            'business_type'               => $request->business_type,
            'district_id'                 => $request->district_id,
            'region_id'                   => $request->region_id,
            'area_id'                     => $request->area_id,
            'territory_id'                => $request->territory_id,
            'trade_license_no'            => $request->trade_license_no,
            'trade_license_validity'      => $request->trade_license_validity,
            'tin'                         => $request->tin,
            'bank_name'                   => $request->bank_name,
            'branch'                      => $request->branch,
            'account_name'                => $request->account_name,
            'account_no'                  => $request->account_no,
            'credit_limit'                => $request->credit_limit,
            'existing_distributor_brands' => $request->existing_distributor_brands,
            'head_code'  => $newHeadCode
        ]);

        return redirect()->route('distributor.index')->with('success', 'Distributor created successfully!');
    }

    public function edit($id)
    {
        $distributor = Distributor::findOrFail($id);
        $districts = District::all();
        $areas = Area::all();
        return view('distributor.edit', compact('distributor','districts','areas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'distributor_name'            => 'required|string|max:255',
            'owner_name'                  => 'required|string|max:255',
            'nid'                         => 'nullable|string|max:50',
            'contact_no'                  => 'required|string|max:20',
            'email'                       => 'nullable|email|max:255',
            'address'                     => 'nullable|string|max:500',
            'business_type'               => 'required|string|in:Sole Proprietorship,Partnership,Private Ltd. Company',
            'district_id'                 => 'nullable|max:10',
            'trade_license_no'            => 'nullable|string|max:100',
            'trade_license_validity'      => 'nullable|date',
            'tin'                         => 'nullable|string|max:50',
            'bank_name'                   => 'nullable|string|max:100',
            'branch'                      => 'nullable|string|max:100',
            'account_name'                => 'nullable|string|max:100',
            'account_no'                  => 'nullable|string|max:100',
            'existing_distributor_brands' => 'nullable|string|max:255',
        ]);

        // Find the distributor
        $distributor = Distributor::findOrFail($id);

        // Update the distributor with form values
        $distributor->update([
            'distributor_name'            => $request->distributor_name,
            'owner_name'                  => $request->owner_name,
            'nid'                         => $request->nid,
            'contact_no'                  => $request->contact_no,
            'email'                       => $request->email,
            'address'                     => $request->address,
            'business_type'               => $request->business_type,
            'district_id'                 => $request->district_id,
            'region_id'                   => $request->region_id,
            'area_id'                     => $request->area_id,
            'territory_id'                => $request->territory_id,
            'trade_license_no'            => $request->trade_license_no,
            'trade_license_validity'      => $request->trade_license_validity,
            'tin'                         => $request->tin,
            'bank_name'                   => $request->bank_name,
            'branch'                      => $request->branch,
            'account_name'                => $request->account_name,
            'account_no'                  => $request->account_no,
            'existing_distributor_brands' => $request->existing_distributor_brands,
        ]);

        return redirect()->route('distributor.index')->with('success', 'Distributor updated successfully.');
    }

    public function destroy($id)
    {
        $pricing = Distributor::findOrFail($id);
        $pricing->delete();
        return redirect()->route('distributor.index')->with('success','Distributor deleted successfully');
    }

    public function getDistributorUsers($id)
    {
        $users = User::role('Distributor')
                    ->whereNull('distributor')
                    ->get();

        return response()->json(['users' => $users]);
    }
    public function assignDistributorBulk(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'distributor_id' => 'required|exists:distributor,id',
        ]);

        User::whereIn('id', $request->user_ids)->update(['distributor' => $request->distributor_id]);

        return response()->json(['message' => 'Distributor assigned successfully!']);
    }

    public function dumIn()
    {
        $dum = User::whereNotNull('distributor')
            ->with(['distributorRelation.district'])
            ->get();

        return view('distributor.dis-map', compact('dum'));
    }

    public function dumData()
    {
        $dum = User::whereNotNull('distributor')
            ->with('distributorRelation.district')
            ->get();

        return response()->json(['data' => $dum]);
    }

    public function dumDestroy($id)
    {
        $user = User::findOrFail($id);

        $user->distributor = null;
        $user->save();

        return redirect()->route('dum.index')->with('success', 'User removed from distributor successfully.');
    }
}
