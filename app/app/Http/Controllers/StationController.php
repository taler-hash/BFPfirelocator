<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditStationRequest;
use App\Http\Requests\StoreStationRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Services\StationService;

class StationController extends Controller
{
    private $stationService;

    public function __construct()
    {
        $this->stationService = new StationService();
    }

    public function display() {
        return Inertia::render('Station/Station');
    }
    
    public function index(Request $request) {
        $stations = $this->stationService->getStations($request);

        return response()->json($stations);
    }

    public function store(StoreStationRequest $request) {
        $this->stationService->storeStation($request);
    }

    public function show() {

    }

    public function edit($id, EditStationRequest $request) {
        $this->stationService->editStation($id, $request);
    }

    public function delete($id) {
        $this->stationService->deleteStation($id);
    }
    

    public function dashboard() {
        
    }

    public function users($id, Request $request) {
        return response()->json($this->stationService->getUsers($id, $request));
    }
}
