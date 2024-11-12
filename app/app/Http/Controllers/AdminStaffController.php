<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminStaffController extends Controller
{

    public function display() {
        return Inertia::render('AdminStaff/AdminStaff');
    }

    public function index() {
    }

    public function create() {

    }

    public function read() {

    }

    public function update() {

    }

    public function delete() {

    }
    

    public function dashboard() {
        
    }
}
