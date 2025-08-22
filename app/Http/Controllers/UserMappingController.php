<?php

namespace App\Http\Controllers;

use App\Models\AreaUser;
use App\Models\RegionUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\TerritoryUser;

class UserMappingController extends Controller
{
    public function index()
    {
        $rsm = User::whereNotNull('region')
                ->with('regionRelation')
                ->get();

        return view('mapping.user-mapping.rsm', compact('rsm'));
    }

    public function data()
    {
        $records = RegionUser::with(['user', 'region'])->get();

        $grouped = $records->groupBy('user_id')->map(function ($items, $userId) {
            $user = $items->first()->user;

            return [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'address' => $user->address,
                'contact_number' => $user->contact_number,
                'regions' => $items->pluck('region.region_name')->implode(', '),
            ];
        })->values();

        return response()->json(['data' => $grouped]);
    }
    
    public function getUserRegions($userId)
    {
        $regions = RegionUser::with('region')
            ->where('user_id', $userId)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'region_name' => $item->region->region_name,
                ];
            });

        return response()->json(['regions' => $regions]);
    }

    public function destroy($id)
    {
        $regionUser = RegionUser::findOrFail($id);
        $regionUser->delete();

        return response()->json(['success' => true]);
    }
    //ASM
    public function asmIn()
    {
        $asm = User::whereNotNull('area')
                ->with('areaRelation')
                ->get();

        return view('mapping.user-mapping.asm', compact('asm'));
    }

    public function asmData()
    {
        $records = AreaUser::with(['user', 'area'])->get();

        $grouped = $records->groupBy('user_id')->map(function ($items, $userId) {
            $user = $items->first()->user;

            return [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'address' => $user->address,
                'contact_number' => $user->contact_number,
                'areas' => $items->pluck('area.area_name')->implode(', '),
            ];
        })->values();

        return response()->json(['data' => $grouped]);
    }

    public function getUserAreas($userId)
    {
        $areas = AreaUser::with('area')
            ->where('user_id', $userId)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'area_name' => $item->area->area_name,
                ];
            });

        return response()->json(['areas' => $areas]);
    }

    public function asmDestroy($id)
    {
        $areaUser = AreaUser::findOrFail($id);
        $areaUser->delete();

        return response()->json(['success' => true]);
    }
    //TSM
    public function tsmIn()
    {
        $tsm = User::whereNotNull('territory')
                 ->with('territoryRelation')
                 ->get();
        return view('mapping.user-mapping.tsm', compact('tsm'));
    }

    public function tsmData()
    {
        $records = TerritoryUser::with(['user', 'territory'])->get();

        $grouped = $records->groupBy('user_id')->map(function ($items, $userId) {
            $user = $items->first()->user;

            return [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'address' => $user->address,
                'contact_number' => $user->contact_number,
                'territories' => $items->pluck('territory.territory_name')->implode(', '),
            ];
        })->values();
        return response()->json(['data' => $grouped]);
    }

    public function getUserTerritories($userId)
    {
        $territories = TerritoryUser::with('territory')
            ->where('user_id', $userId)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'territory_name' => $item->territory->territory_name
                ];
            });

        return response()->json(['territories' => $territories]);
    }

    public function deleteUserTerritory($id)
    {
        $territoryUser = TerritoryUser::findOrFail($id);
        $territoryUser->delete();

        return response()->json(['success' => true]);
    }

}
