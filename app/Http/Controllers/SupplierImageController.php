<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SupplierImageController extends Controller
{
    public function image($imageName){
        $path = public_path().'/uploads/clients/'.$imageName;
        return Response::download($path);     
    }
}
