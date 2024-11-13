<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //

    public function register(Request $request){

    }

    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            "email" => "required|email",
            "password" => "required|string|min:6"
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => $validator->errors()->all(), 400
            ]);
        }
    }
}
