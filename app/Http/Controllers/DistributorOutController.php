<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use App\Models\DistributorIn;
use App\Models\District;
use App\Models\Retail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\ImeiInfo;
use Illuminate\Support\Facades\Log;

class DistributorOutController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->distributor) {
            // User has a distributor, show only their data
            $distributor = Distributor::find($user->distributor);
            $districtId = $distributor->district_id ?? null;

            $retails = Retail::where('district_id', $districtId)->get();
            $imeis = DistributorIn::where('status', 1)
                ->where('distributor_id', $distributor->id)
                ->get();
        } else {
            // No distributor set, show all
            $retails = Retail::all();
            $imeis = DistributorIn::where('status', 1)->get();
        }

        return view('distributor-out.index', compact('retails', 'imeis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.imei_1' => 'required|string|distinct',
            'items.*.retail_id' => 'required|exists:retails,id',
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->items as $item) {
                $imei = $item['imei_1'];
                $retailId = $item['retail_id'];
                $now = Carbon::now();

                DistributorIn::where('imei_1', $imei)->update([
                    'retail_id' => $retailId,
                    'status' => 0,
                    'distributor_out' => $now,
                    'retail_status' => 1,
                ]);

                ImeiInfo::where('imei_1', $imei)->update([
                    'dealer_out' => $now,
                    'dealer_state' => 2,
                    'retail' => $retailId,
                    'retail_in' => $now,
                    'retail_state' => 0,
                ]);
            }

            DB::commit();
            return back()->with('success', 'Distributor Out Successful');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function getImeiInfo($imei)
    {
        $user = auth()->user();

        $imeiData = DistributorIn::where('imei_1', $imei)->first();

        if (!$imeiData) {
            return response()->json(['message' => 'IMEI not found'], 422);
        }

        if ($user->distributor && $user->distributor !== $imeiData->distributor_id) {
            return response()->json(['message' => 'This IMEI is not in your stock'], 422);
        }

        if ($imeiData->status == 0) {
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
        ]);
    }
}
