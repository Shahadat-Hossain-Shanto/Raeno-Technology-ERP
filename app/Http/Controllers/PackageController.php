<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{


    public function getPackages()
    {
        // Retrieve packages from the database
        $packages = Package::all();

        // Transform the retrieved packages to match the desired format
        $formattedPackages = $packages->map(function ($package) {
            // Remove single quotes from the details and decode JSON
            $details = json_decode(trim($package->details, "'"), true);

            return [
                'package' => $package->package,
                'price' => $package->price,
                'type' => $package->type,
                'details' => $details
            ];
        });

        return response()->json($formattedPackages);
    }
}
