<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class BrgyStaffController extends Controller
{
    public function index() {
        return Inertia::render('BrgyStaff/brgystaff');
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
