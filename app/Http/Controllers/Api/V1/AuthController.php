<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Session;
use Carbon\Carbon;
use Exception;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use PhpParser\Node\Expr\Throw_;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthController extends Controller
{
    

    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            "name" => "required|string",
            "email" => [
                "required",
                "email",
                Rule::unique('users')->where('is_deleted', false)
            ],
            "phone_number" => "required|string",
            "password" => [
                "required", 
                "string", 
                Password::min(8)->mixedCase()
                ->numbers()
                ->symbols(),
                "confirmed"
            ]
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => "Validación fallida",
                "message" => $validator->errors()->all()
            ],400);
        }

        try{
            $name = $request->input("name");
            $email = $request->input("email");
            $phoneNumber = $request->input("phone_number");
            $password = $request->input("password");
    
            $user = new User();
            $user->name = $name;
            $user->email = $email;
            $user->phone_number = $phoneNumber;
            $user->password = Hash::make($password);
            $user->is_active = true;
            $user->is_deleted = false;
            $user->save();
    
            return response()->json([
                "success" => true,
                "message" => "Usuario registrado correctamente"
            ]);

        }catch(Throwable $ex){
            return response()->json([
                "error" => "Error Interno de Servidor",
                "message" => "Algo malo sucedió. Por favor intentalo nuevamente",
                "details" => $ex->getMessage()
            ],500);
        }




    }

    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            "email" => "required|email",
            "password" => [
                "required", 
                "string", 
                Password::min(8)->mixedCase()
                ->numbers()
                ->symbols()
            ]
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

           
           
            $session = new Session();
            $session->user_system_id = null;
            $session->ip_adress = $request->ip();
            $session->user_agent = $request->header('User-Agent');
            $session->auth_token = null;
            $session->is_active = true;
            $session->is_deleted = false;
            $session->expires_at = null;
            $session->authenticated_at = now();
            $session->accessed_at = null;
            $session->logout_at = null;
            $session->last_activity = now();
            $session->login_attempts = 0;
            $session->save();

            $customClaims = [
                'session_id' => $session->id,
            ];
            $token = JWTAuth::claims($customClaims)->fromUser($user);


            $decodedToken = JWTAuth::setToken($token)->getPayload();
            $expiresAt = Carbon::createFromTimestamp($decodedToken['exp']); // Usamos Carbon para convertir el timestamp en una fecha
           
            $session->auth_token = $token;
            $session->expires_at = $expiresAt;
            $session->save();


            return response()->json([
                "success" => true,
                "token" => $token,
                "user_system_id" => null
            ]);

        }catch(Throwable $ex){
            return response()->json([
                "error" => "Error Interno de Servidor",
                "message" => "Algo malo sucedió. Por favor intentalo nuevamente",
                "details" => $ex->getMessage()
            ],500);

        }
    }

    public function choseeSystem(Request $request){
        
        $validator = Validator::make($request->all(),[
            "user_system_id" => "required|integer"
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => "Validación fallida",
                "message" => $validator->errors()->all()
            ],400);
        }

        try{

            $token = $request->header("token");
            $userSystemId = $request->input("user_system_id");

            $decodedToken = JWTAuth::setToken($token)->getPayload();
            $sessionId = $decodedToken["session_id"];
            if(!$sessionId){
                return response()->json([
                    "error" => "Recurso no encontrado",
                    "message" => "No fue posible obtener la session_id almacenada en el token"
                ],400);
            }

            $session = Session::find($sessionId);
            if(!$session){
                return response()->json([
                    "error" => "Recurso no encontrado",
                    "message" => "No se encontró la sesión para el usuario logueado"
                ],404);
            }

            $session->user_system_id = $userSystemId;
            $session->accessed_at = now();
            $session->last_activity = now();
            $session->save();
            
            return response()->json([
                "success" => true,
                "message" => "Se asignó correctamente el sistema a la sesión",
                "user_system_id" => (int) $session->user_system_id
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
