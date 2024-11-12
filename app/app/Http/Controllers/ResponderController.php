<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ResponderController extends Controller
{
    public function display() {
        return Inertia::render('Responder/Responder');
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
