<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\AreaUser;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AreaController extends Controller
{
    // Show all areas
    public function index()
    {
        $areas = Area::all();
        return view('mapping.areas.index', compact('areas'));
    }

    // Show create form
    public function create()
    {
        $regions = Region::where('status', 1)->get();
        return view('mapping.areas.create', compact('regions'));
    }

    // Store new area
    public function store(Request $request)
    {
        $request->validate([
            'region_name' => 'required|string|max:255',
            'area_name' => 'required|string|max:255|unique:areas,area_name',
        ]);

        Area::create([
            'region_name' => $request->region_name,
            'area_name' => $request->area_name,
            'status' => 1,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('areas.index')->with('success', 'Area created successfully.');
    }

    // Show edit form
    public function edit($id)
    {
        $area = Area::findOrFail($id);
        $regions = Region::where('status', 1)->get();
        return view('mapping.areas.edit', compact('area', 'regions'));
    }

    // Update existing area
    public function update(Request $request, $id)
    {
        $request->validate([
            'region_name' => 'required|string|max:255',
            'area_name' => 'required|string|max:255|unique:areas,area_name,' . $id,
            'status' => 'required|in:0,1',
        ]);

        $area = Area::findOrFail($id);
        $area->update([
            'region_name' => $request->region_name,
            'area_name' => $request->area_name,
            'status' => $request->status,
        ]);

        return redirect()->route('areas.index')->with('success', 'Area updated successfully.');
    }

    // Delete area
    public function destroy($id)
    {
        $area = Area::findOrFail($id);
        $area->delete();

        return redirect()->route('areas.index')->with('success', 'Area deleted successfully.');
    }

    // Return area data as JSON
    public function data()
    {
        $areas = Area::all();
        return response()->json(['data' => $areas]);
    }

    public function getAreaUsers($id)
    {
        $users = User::role('ASM')
                    // ->whereNull('area')
                    ->get();

        return response()->json(['users' => $users]);
    }

    public function assignAreaBulk(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'area_id' => 'required|exists:areas,id',
        ]);

        foreach ($request->user_ids as $userId) {
            $exists = AreaUser::where('user_id', $userId)
                ->where('area_id', $request->area_id)
                ->exists();

            if (!$exists) {
                AreaUser::create([
                    'user_id' => $userId,
                    'area_id' => $request->area_id,
                ]);
            }
        }

        return response()->json(['message' => 'Area assigned to selected users successfully!']);
    }
}
