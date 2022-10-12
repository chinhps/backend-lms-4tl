<?php

namespace App\Http\Controllers;

use App\Repositories\User\UserInterface;


class UserController extends Controller
{
    public UserInterface $userRepository;

    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function ListUsers()
    {
        $data = $this->userRepository->getList();
        return response()->json([
            "data" => $data
        ]);
    }
}
