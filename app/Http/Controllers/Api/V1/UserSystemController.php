<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\SystemResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserSystemController extends Controller
{
    public function getSystemsByToken(Request $request){
        try{

            $token = $request->header("token");
            $user = JWTAuth::setToken($token)->authenticate();
            if(!$user){
                return response()->json([
                "error" => "Recurso no encontrado",
                "message" => "No se encontró el usuario",
                ],404);
            }
            $systems = $user->systems()->select(["user_systems.id as user_system_id","name","description"])->where("user_systems.is_deleted",false)->get();
            return response()->json([
                'success' => true,
                'data' => SystemResource::collection($systems),
                'message' => 'Los sistemas se han obtenido correctamente.'
            ]);

        }catch(Throwable $ex){
            return response()->json([
                "error" => "Error Interno de Servidor",
                "message" => "Algo malo sucedió. Por favor intentalo nuevamente",
                "details" => $ex->getMessage()
            ],500);
        }
        
    }

    public function getSystemsByUser($userId){
        $validator = Validator::make(['user_id' => $userId],[
            "user_id" => "required|numeric",
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => "Validación fallida",
                "message" => $validator->errors()->all()
            ],400);
        }

        try{
            //recurso no encontrado , diferente que 401 que es solo para authentication
            $user = User::find($userId);
            if(!$user){
                return response()->json([
                "error" => "Recurso no encontrado",
                "message" => "No se encontró el usuario",
                ],404);
            }

            $systems = $user->systems()->select(["user_systems.id as user_system_id","name","description"])->where("user_systems.is_deleted",false)->get();
            return response()->json([
                'success' => true,
                'data' => SystemResource::collection($systems),
                'message' => 'Los sistemas se han obtenido correctamente.'
            ]);

        }catch(Throwable $ex){
            return response()->json([
                "error" => "Error Interno de Servidor",
                "message" => "Algo malo sucedió. Por favor intentalo nuevamente",
                "details" => $ex->getMessage()
            ],500);
        }
        
    }

}
