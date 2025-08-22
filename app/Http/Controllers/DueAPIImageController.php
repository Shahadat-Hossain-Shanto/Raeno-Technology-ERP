<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Log;

class DueAPIImageController extends Controller
{
    public function downloadImage($imageName){
        $path = public_path().'/uploads/dues/'.$imageName;
        return Response::download($path);     
    }
}
