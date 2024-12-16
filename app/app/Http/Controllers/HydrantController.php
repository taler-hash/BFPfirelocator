<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HydrantService;
use Inertia\Inertia;
use App\Http\Requests\StoreHydrantRequest;
use App\Http\Requests\EditHydrantRequest;

class HydrantController extends Controller
{
    private $hydrantService;

    public function __construct()
    {
        $this->hydrantService = new HydrantService();
    }

    public function display() {
        return Inertia::render('Hydrant/Hydrant');
    }
    
    public function index(Request $request) {
        $hydrants = $this->hydrantService->getHydrants($request);

        return response()->json($hydrants);
    }

    public function store(StoreHydrantRequest $request) {
        $this->hydrantService->storeHydrant($request);
    }

    public function show() {

    }

    public function edit($id, EditHydrantRequest $request) {
        $this->hydrantService->editHydrant($id, $request);
    }

    public function delete($id) {
        $this->hydrantService->deleteHydrant($id);
    }
}

