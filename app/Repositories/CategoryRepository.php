<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\UserUnauthorizedException;
use Config;

class CategoryRepository
{
    public function search($inputs)
    {
        return Category::when(isset($inputs['id']), function ($query) use ($inputs) {
            return $query->where('id', $inputs['id']);
        })
        ->when(isset($inputs['name']), function ($query) use ($inputs) {
            return $query->where('name', 'LIKE', '%' . $inputs['name'] . '%');
        })
        ->orderBy('name', 'desc')
        ->paginate(2);
    }
    
    public function store($inputs)
    {
        return Category::create([
            'name'      => $inputs['name'],
            'status'    => 1
        ]);
    }

    public function show($id)
    {
        return Category::findOrFail($id);
    }

    public function update($inputs, $id)
    {
        return Category::findOrFail($id)
            ->update($inputs);
    }
}
