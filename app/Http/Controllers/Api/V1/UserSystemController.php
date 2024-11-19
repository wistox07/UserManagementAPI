<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserSystemController extends Controller
{
    public function getSystemsByUser(Request $request){
        try{

            $token = $request->header("token");
            //dd($token);
            //$token = JWTAuth::claims($customData)->fromUser($user);
            $user = JWTAuth::setToken($token)->authenticate();
            return $user->systems;
            //dd($result);

        }catch(Throwable $ex){
            return response()->json([
                "error" => "Error Interno de Servidor",
                "message" => "Algo malo sucedió. Por favor intentalo nuevamente",
                "details" => $ex->getMessage()
            ]);
        }
        
    }

}
