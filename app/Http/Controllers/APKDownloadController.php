<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class APKDownloadController extends Controller
{

    public function index($apk){
        $path = 'uploads/apk/'.$apk;
        return Response::download($path, $apk, ['Content-Type: application/vnd.android.package-archive']);
    }

}
