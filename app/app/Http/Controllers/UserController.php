<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public $userService;

    public function __construct()
    {
        $this->userService = new UserService;
    }

    public function index(Request $request) {
        return response()->json($this->userService->getUsers($request));
    }

    public function store(StoreUserRequest $request) {
        $this->userService->storeUser($request);
    }

    public function show($id) {
        return response()->json($this->userService->getUser($id));
    }

    public function edit($id, EditUserRequest $request) {
        $this->userService->editUser($id, $request);
    }

    public function delete($id) {
        $this->userService->deleteUser($id);
    }
}
