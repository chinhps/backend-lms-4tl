<?php 

namespace App\Repositories\User;

use App\Models\User;
use App\Http\Requests\Request;

class UserRepository implements UserInterface {
    
    public function getList() {
        return User::get();
    }

}