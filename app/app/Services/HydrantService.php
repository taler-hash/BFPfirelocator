<?php 

namespace App\Services;
use App\Models\Hydrant;

class HydrantService {

    public function getHydrants($request)
    {
        if($request?->all) {
            return Hydrant::all();
        }

        $model = new Hydrant();

        return Hydrant::whereAny($model->getFillable(), 'LIKE', "%{$request->searchString}%")
        ->orderBy($request->sortBy, $request->sortType)
        ->paginate($request->rows);
    }

    public function storeHydrant($request)
    {
        Hydrant::create($request->all());
    }

    public function editHydrant($id, $request)
    {
        Hydrant::find($id)->update($request->all());
    }

    public function deleteHydrant($id)
    {
        Hydrant::find($id)->delete();
    }
}