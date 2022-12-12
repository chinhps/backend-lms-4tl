<?php

namespace App\Http\Controllers\LoginWith;

use App\Http\Controllers\BaseResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginGoogleController extends Controller
{

    public function callback(Request $request)
    {
        try {
            $state = $request->input('state');

            parse_str($state, $result);
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::where('email', $googleUser->email)->first();
            if ($user) {
                Auth::login($user,true);
                return $request->user();
            } else {
                return BaseResponse::ResWithStatus("Không có tài khoản này trên hệ thống!",403);
            }

        } catch (\Exception $exception) {
            return BaseResponse::ResWithStatus("Lỗi từ phía Google! Vui lòng thử lại!",403);
        }
    }

    public function getGoogleSignInUrl()
    {
        try {
            $url = Socialite::driver('google')->stateless()
                ->redirect()->getTargetUrl();
            return response()->json([
                'url' => $url,
            ])->setStatusCode(Response::HTTP_OK);
        } catch (\Exception $exception) {
            return $exception;
        }
    }

}