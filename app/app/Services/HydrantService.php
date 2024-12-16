<?php 

namespace App\Services;

class HydrantService {

    public function getHydrants($request)
    {
        $model = new Hydrant();

        return Hydrant::whereNotIn('id', [1])
        ->whereAny($model->getFillable(), 'LIKE', "%{$request->searchString}%")
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