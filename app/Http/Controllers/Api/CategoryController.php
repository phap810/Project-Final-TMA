<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;
use App\Http\Resources\category\CategoryResource;
use App\Http\Resources\category\CategoryCollection;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\BaseResource;
use Session;

class CategoryController extends Controller
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function search(CategoryRequest $request)
    {
        $sessions = Session::put("cart.products", [['id' => 1, 'item' => 1]]);
        dd($sessions);
        return new CategoryCollection($this->categoryRepository->search($request->searchFilter()));
    }

    public function store(CategoryRequest $request)
    {
        return new BaseResource($this->categoryRepository->store($request->storeFilter()));
    }

    public function show($id)
    {
        return new CategoryResource($this->categoryRepository->show($id));
    }

    public function update(CategoryRequest $request, $id)
    {
        return new BaseResource($this->categoryRepository->update($request->updateFilter(), $id));
    }
}

