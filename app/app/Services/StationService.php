<?php

namespace App\Services;

use App\Models\Station;
use App\Models\User;

class StationService
{

    public function getStations($request)
    {
        $model = new Station();

        return Station::whereAny($model->getFillable(), 'LIKE', "%{$request->searchString}%")
        ->orderBy($request->sortBy, $request->sortType)
        ->paginate($request->rows);
    }

    public function storeStation($request) {
        Station::create($request->all());
    }

    public function getUsers($id, $request) {
        $model = new User();

        return User::where('station_id', $id)
        ->with('roles')
        ->whereHas('roles', function ($q) use ($request) {
            $q->where('name' , $request->role);
        })
        ->whereAny($model->getFillable(), 'LIKE', "%{$request->searchString}%")
        ->orderBy($request->sortBy, $request->sortType)
        ->paginate($request->rows);
    }
}
