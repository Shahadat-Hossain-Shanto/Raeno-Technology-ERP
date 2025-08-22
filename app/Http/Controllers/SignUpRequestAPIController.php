<?php

namespace App\Http\Controllers;

use App\Models\SignUpRequst;
use Illuminate\Http\Request;
use App\Http\Services\SignUpService;

class SignUpRequestAPIController extends Controller
{
    public function store(Request $request){
        return (new SignUpService)->store($request);
    }
}
