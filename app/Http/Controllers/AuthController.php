<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLogin;
use App\Http\Requests\UserRegister;
use App\Models\User;
use App\Repositories\User\UserInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public UserInterface $userRepository;

    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function Login(UserLogin $request)
    {
        $validated = $request->validated();
        if (Auth::attempt($validated)) {
            session()->regenerate();
            return $request->user();
        }
        return response()->json(['msg' => 'Dang nhap that bai'], 400);
    }

    // Dang ky
    public function Register(UserRegister $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        $user = User::create([
            ...$validated,
            'status' => 1,
            'role_id' => 1,
            'class_id' => 1
        ]);
        return response()->json(['user' => $user, 'msg' => 'Tao tai khoan thanh cong!']);
    }

    public function getMe() {
        $data_user = $this->userRepository->infoMe();
        return BaseResponse::ResWithStatus($data_user);
    }

    public function ListUsers()
    {
        $data = $this->userRepository->getList();
        return response()->json([
            "data" => $data
        ]);
    }
}
