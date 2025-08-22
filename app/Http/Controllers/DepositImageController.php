<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Log;

use App\Models\DepositImage;

class DepositImageController extends Controller
{
    public function store(Request $req){

        if($files=$req->file('images')){
            foreach($files as $image){

                $image1 = new DepositImage;

                // if ($image -> hasFile('image')) {
                    // $file = $image -> file ('image');

                    $file = $image;
                    $extension = $file->getClientOriginalExtension();
                    $size = $file->getSize();
                    // $filename = time() . '.' .$extension;
                    $filename = $file->getClientOriginalName();
                    $file->move('uploads/deposits/', $filename);
                    $image1->imageName  = $filename;
                // }

                $image1->extension = $extension;
                $image1->size = $size;
                $image1->deposit_id = app('App\Http\Controllers\DepositAPIController')->getDepositId();

                $image1->save();
            }
        }

        return response() -> json([
            'status'=>200,
            'message' => 'Deposit Image created Successfully!'
        ]);
    }

    public function downloadImage($imageName){
        $path = public_path().'/uploads/deposits/'.$imageName;
        return Response::download($path);     
    }
}
