<?php

namespace App\Http\Middleware;

use Closure;
use Facades\App\Libraries\Session;
use App\Exceptions\UserUnauthorizedException;
use App\Models\SessionUser;

class Auth
{
    public function handle($request, Closure $next)
    {
        $token = $request->header('token');
        $checkTokenIsValid = SessionUser::where('token', $token)->first();
        if(empty($token)){
            return response()->json([
                'code' => 500,
                'message' => 'Token không được gửi thông qua header'
            ], 500);
        }elseif(empty($checkTokenIsValid)){
            return response()->json([
                'code' => 500,
                'message' => 'Token không hợp lệ'
            ], 500);
        }else{
             return $next($request);
        }
    }
}
