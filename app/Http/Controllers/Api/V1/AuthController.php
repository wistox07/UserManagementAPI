<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthController extends Controller
{
    

    public function register(Request $request){

    }

    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            "email" => "required|email",
            "password" => "required|string|min:6"
        ]);

        //Un error “400 Bad Request” significa que el servidor no puede procesar la solicitud debido a problemas del lado del cliente
        if($validator->fails()){
            return response()->json([
                "error" => "Validación fallida",
                "message" => $validator->errors()->all()
            ], 400);
        }

        try{

            $user = User::where("email", $request->email)
            ->where("is_deleted", false)
            ->first();
            
            // El código de error HTTP 401 indica que la petición (request) no ha sido ejecutada porque carece de credenciales válidas de autenticación
            if(!$user){
                return response()->json([
                    "error" => "Validación fallida",
                    "message" => 'Usuario no encontrado'
                ],401);
            }

            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'error' => 'No autorizado',
                    'message' => 'Credenciales Invalidas',
                ], 401);
            }
         
           

            if (!$user->is_active) {
                return response()->json([
                    'error' => 'Acceso denegado',
                    'message' => 'La cuenta del usuario no está activa',
                ], 403);
            }

           
            $token = JWTAuth::fromUser($user);
            $decodedToken = JWTAuth::setToken($token)->getPayload();
            $expiresAt = Carbon::createFromTimestamp($decodedToken['exp']); // Usamos Carbon para convertir el timestamp en una fecha


            $session = new Session();
            $session->user_system_id = null;
            $session->ip_adress = $request->ip();
            $session->user_agent = $request->header('User-Agent');
            $session->auth_token = $token;
            $session->is_active = true;
            $session->is_deleted = false;
            $session->expires_at = $expiresAt;
            $session->authenticated_at = now();
            $session->accessed_at = null;
            $session->logout_at = null;
            $session->last_activity = now();
            $session->login_attempts = 0;
            $session->save();

            return response()->json([
                "success" => true,
                "token" => $token
            ]);

        }catch(Throwable $ex){
            return response()->json([
                "error" => "Error Interno de Servidor",
                "message" => "Algo malo sucedió. Por favor intentalo nuevamente",
                "details" => $ex->getMessage()
            ]);

        }

       

  




        
    }
}
