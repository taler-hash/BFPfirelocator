<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function index() {
        return response()->json(Role::whereNotIn('id', [1])->get());
    }
}
