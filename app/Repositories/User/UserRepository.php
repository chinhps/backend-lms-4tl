<?php 

namespace App\Repositories\User;

use App\Models\User;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class UserRepository implements UserInterface {
    
    public function infoMe()
    {
        return Auth::user();
    }
    public function getList() {
        return User::get();
    }

}