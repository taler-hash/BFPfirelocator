<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    public function getUsers($request): LengthAwarePaginator
    {
        $model = new User();

        $users = User::when($request?->stationId, function ($q) use ($request) {
            $q->where('station_id', $request->stationId);
        })
        ->with([
            'station',
            'roles'
        ])
        ->whereHas('roles', function ($q) use ($request) {
            $q->when($request?->role, function ($q2) use ($request) {
                $q2->where('name', $request->role);
            });
        })
        ->wherehas('station', function ($q) use ($request) {
            $q->when($request->stationId, function ($q2) use ($request) {
                $q2->where('id', $request->stationId);
            });
        })
        
        ->whereAny($model->getFillable(), 'LIKE', "%{$request->searchString}%")
        ->orderBy($request->sortBy, $request->sortType)
        ->paginate($request->rows);

        dd($users);

        return $users;
    }

    public function storeUser($request): void
    {
        $user = User::create($request->all());

        $user->assignRole($request->role);
    }

    public function getUser($id): User
    {
        return User::where('id', $id)->first();
    }

    public function editUser($id, $request): void
    {
        User::find($id)->update($request->all());
    }

    public function deleteUser($id): void
    {
        User::find($id)->delete();
    }
}
