<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\SystemResource;
use Illuminate\Http\Request;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserSystemController extends Controller
{
    public function getSystemsByUser(Request $request){
        try{

            $token = $request->header("token");
            $user = JWTAuth::setToken($token)->authenticate();
            $systems = $user->systems()->select(["system_id","name","description"])->get();
            
            return SystemResource::collection($systems);

        }catch(Throwable $ex){
            return response()->json([
                "error" => "Error Interno de Servidor",
                "message" => "Algo malo sucedió. Por favor intentalo nuevamente",
                "details" => $ex->getMessage()
            ]);
        }
        
    }

}
