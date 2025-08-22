<?php

namespace App\Http\Controllers;

use App\Models\Territory;
use App\Models\Region;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Models\TerritoryUser;

class TerritoryController extends Controller
{
    // Show all territories
    public function index()
    {
        $territories = Territory::all();
        return view('mapping.territories.index', compact('territories'));
    }

    // Show create form
    public function create()
    {
        $regions = Region::where('status', 1)->get();
        $areas = Area::where('status', 1)->get();
        return view('mapping.territories.create', compact('regions', 'areas'));
    }

    // Store new territory
    public function store(Request $request)
    {
        $request->validate([
            'region_name' => 'required|string|max:255',
            'area_name' => 'required|string|max:255',
            'territory_name' => 'required|string|max:255|unique:territories,territory_name',
        ]);

        Territory::create([
            'region_name' => $request->region_name,
            'area_name' => $request->area_name,
            'territory_name' => $request->territory_name,
            'status' => 1,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('territories.index')->with('success', 'Territory created successfully.');
    }

    // Show edit form
    public function edit($id)
    {
        $territory = Territory::findOrFail($id);
        $regions = Region::where('status', 1)->get();
        $areas = Area::where('status', 1)->get();
        return view('mapping.territories.edit', compact('territory', 'regions', 'areas'));
    }

    // Update existing territory
    public function update(Request $request, $id)
    {
        $request->validate([
            'region_name' => 'required|string|max:255',
            'area_name' => 'required|string|max:255',
            'territory_name' => 'required|string|max:255|unique:territories,territory_name,' . $id,
            'status' => 'required|in:0,1',
        ]);

        $territory = Territory::findOrFail($id);
        $territory->update([
            'region_name' => $request->region_name,
            'area_name' => $request->area_name,
            'territory_name' => $request->territory_name,
            'status' => $request->status,
        ]);

        return redirect()->route('territories.index')->with('success', 'Territory updated successfully.');
    }

    // Delete territory
    public function destroy($id)
    {
        $territory = Territory::findOrFail($id);
        $territory->delete();

        return redirect()->route('territories.index')->with('success', 'Territory deleted successfully.');
    }

    // Return territory data as JSON
    public function data()
    {
        $territories = Territory::all();
        return response()->json(['data' => $territories]);
    }

    public function getTerritoryUsers($id)
    {
        $users = User::role('TSM')
                    // ->whereNull('territory')
                    ->get();

        return response()->json(['users' => $users]);
    }

    public function assignTerritoryBulk(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'territory_id' => 'required|exists:territories,id',
        ]);

        foreach ($request->user_ids as $userId) {
            $exists = TerritoryUser::where('user_id', $userId)
                ->where('territory_id', $request->territory_id)  // fixed here
                ->exists();

            if (!$exists) {
                TerritoryUser::create([
                    'user_id' => $userId,
                    'territory_id' => $request->territory_id,   // fixed here
                ]);
            }
        }

        return response()->json(['message' => 'Territory assigned to selected users successfully!']);
    }
}
