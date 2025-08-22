<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Log;

use App\Models\ClientImage;



class ClientImageController extends Controller
{
    public function store(Request $req){

    Log::info( $req->type."         ------------------------------------Client Image-------------------------------       ");

        if($req->type == 'salary'){

            $image = new ClientImage;

            if ($req -> hasFile('image')) {
                $file = $req -> file ('image');
                $extension = $file->getClientOriginalExtension();
                $size = $file->getSize();
                // $filename = time() . '.' .$extension;
                // $filename = time() . '.' .$file->getClientOriginalName();
                $filename = $file->getClientOriginalName();

                $file->move('uploads/salary/', $filename);
                $image->imageName  = $filename;
            }
            $image->type = $req->type;
            $image->extension = $extension;
            $image->size = $size;

            $image->save();
        }elseif($req->type == 'customer'){

            $image = new ClientImage;

            if ($req -> hasFile('image')) {
                $file = $req -> file ('image');
                $extension = $file->getClientOriginalExtension();
                $size = $file->getSize();
                // $filename = time() . '.' .$extension;
                // $filename = time() . '.' .$file->getClientOriginalName();
                $filename = $file->getClientOriginalName();

                $file->move('uploads/clients/', $filename);
                $image->imageName  = $filename;
            }

            $image->type = $req->type;
            $image->extension = $extension;
            $image->size = $size;

            $image->save();
        }elseif($req->type == 'supplier'){

            $image = new ClientImage;
            
            if ($req -> hasFile('image')) {
                $file = $req -> file ('image');
                $extension = $file->getClientOriginalExtension();
                $size = $file->getSize();
                // $filename = time() . '.' .$extension;
                // $filename = time() . '.' .$file->getClientOriginalName();
                $filename = $file->getClientOriginalName();

                $file->move('uploads/suppliers/', $filename);
                $image->imageName  = $filename;
            }

            $image->type = $req->type;
            $image->extension = $extension;
            $image->size = $size;

            $image->save();
        }elseif($req->type == 'due'){
            $image = new ClientImage;
            
            if ($req -> hasFile('image')) {
                $file = $req -> file ('image');
                $extension = $file->getClientOriginalExtension();
                $size = $file->getSize();
                // $filename = time() . '.' .$extension;
                // $filename = time() . '.' .$file->getClientOriginalName();
                $filename = $file->getClientOriginalName();

                $file->move('uploads/dues/', $filename);
                $image->imageName  = $filename;
            }

            $image->type = $req->type;
            $image->extension = $extension;
            $image->size = $size;

            $image->save();
        }elseif($req->type == 'deposit'){
            // if($files=$req->file('images')){
            //     foreach($files as $image){
            //         $image1 = new ClientImage;
            //         // if ($image -> hasFile('image')) {
            //             // $file = $image -> file ('image');
            //             $file = $image;
            //             $extension = $file->getClientOriginalExtension();
            //             $size = $file->getSize();
            //             // $filename = time() . '.' .$extension;
            //             $filename = $file->getClientOriginalName();
            //             $file->move('uploads/deposits/', $filename);
            //             $image1->imageName  = $filename;
            //         // }
            //         $image1->type = $req->type;
            //         $image1->extension = $extension;
            //         $image1->size = $size;
            //         // $image1->deposit_id = app('App\Http\Controllers\DepositAPIController')->getDepositId();
            //         $image1->save();
            //     }
            // }

            $image = new ClientImage;
            
            if ($req -> hasFile('image')) {
                $file = $req -> file ('image');
                $extension = $file->getClientOriginalExtension();
                $size = $file->getSize();
                // $filename = time() . '.' .$extension;
                // $filename = time() . '.' .$file->getClientOriginalName();
                $filename = $file->getClientOriginalName();

                $file->move('uploads/deposits/', $filename);
                $image->imageName  = $filename;
            }

            $image->type = $req->type;
            $image->extension = $extension;
            $image->size = $size;

            $image->save();
        }elseif($req->type == '"expense"'){

            // Log::info("asdasdfjshfbk");
            Log::info($req->type);



            $image = new ClientImage;
            
            if ($req -> hasFile('image')) {
                $file = $req -> file ('image');
                $extension = $file->getClientOriginalExtension();
                $size = $file->getSize();
                // $filename = time() . '.' .$extension;
                // $filename = time() . '.' .$file->getClientOriginalName();
                $filename = $file->getClientOriginalName();

                $file->move('uploads/expenses/', $filename);
                $image->imageName  = $filename;
                $image->type = $req->type;

                $image->extension = $extension;
                $image->size = $size;

                $image->save();
            }

            
        }else{
            return response() -> json([
                'status'=>200,
                'message' => 'Image not created!',
            ]);
        }

        return response() -> json([
            'status'=>200,
            'message' => 'Image created Successfully!',
            // 'description' => $req->description
        ]);
    }

    public function image($imageName){
        $path = public_path().'/uploads/clients/'.$imageName;
        return Response::download($path);     
    }
}
