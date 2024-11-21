<?php

namespace App\Http\Middleware;

use App\Models\Session;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Throwable;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ValidateToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try{
            $token = $request->header("token");
            if(!$token){
                return response()->json(['error' => 'Token no enviado'], 401);
            }
            JWTAuth::setToken($token)->authenticate();
            
            $decodedToken = JWTAuth::setToken($token)->getPayload()->toArray();
            $sessionId = $decodedToken["session_id"];

            $isActive = Session::where("id",$sessionId)->value("is_active");
            if(!$isActive){

                return response()->json([
                    'error' => 'Sesión caduca'
                ], 401); // Código HTTP 401 para sesión caducada
            }




        }catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token expirado'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token invalido'], 401);
        } catch (Exception $e) {
            return response()->json(['error' =>  $e->getMessage()], 401);
        }
        
        return $next($request);
   

    }
}
