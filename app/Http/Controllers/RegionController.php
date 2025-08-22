<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\RegionUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class RegionController extends Controller
{
    // Show all regions
    public function index()
    {
        $regions = Region::all();
        $roles = Role::where('name', 'RSM')->get();

        return view('mapping.regions.index', compact('regions', 'roles'));
    }

    // Show create form
    public function create()
    {
        return view('mapping.regions.create');
    }

    // Store new region
    public function store(Request $request)
    {
        $request->validate([
            'region_name' => 'required|string|max:255|unique:regions,region_name',
        ]);

        Region::create([
            'region_name' => $request->region_name,
            'status' => 1,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('regions.index')->with('success', 'Region created successfully.');
    }

    // Show edit form
    public function edit($id)
    {
        $region = Region::findOrFail($id);
        return view('mapping.regions.edit', compact('region'));
    }

    // Update existing region
    public function update(Request $request, $id)
    {
        $request->validate([
            'region_name' => 'required|string|max:255|unique:regions,region_name,' . $id,
            'status' => 'required|in:0,1',
        ]);

        $region = Region::findOrFail($id);
        $region->update([
            'region_name' => $request->region_name,
            'status' => $request->status,
        ]);

        return redirect()->route('regions.index')->with('success', 'Region updated successfully.');
    }

    // Delete region
    public function destroy($id)
    {
        $region = Region::findOrFail($id);
        $region->delete();

        return redirect()->route('regions.index')->with('success', 'Region deleted successfully.');
    }

    // Return region data as JSON (for DataTables or APIs)
    public function data()
    {
        $regions = Region::all();
        return response()->json(['data' => $regions]);
    }

    public function getRegionUsers($id)
    {
        $users = User::role('RSM')
                    // ->whereNull('region')
                    ->get();

        return response()->json(['users' => $users]);
    }

    public function assignRegionBulk(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'region_id' => 'required|exists:regions,id',
        ]);

        foreach ($request->user_ids as $userId) {
            $exists = RegionUser::where('user_id', $userId)
                ->where('region_id', $request->region_id)
                ->exists();

            if (!$exists) {
                RegionUser::create([
                    'user_id' => $userId,
                    'region_id' => $request->region_id,
                ]);
            }
        }

        return response()->json(['message' => 'Region assigned to selected users successfully!']);
    }

}
