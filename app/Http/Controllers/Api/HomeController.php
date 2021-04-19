<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ProductRepository;
use App\Http\Resources\product\ProductResource;
use App\Http\Resources\product\ProductCollection;

class HomeController extends Controller
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    public function show($id)
    {
        return new ProductCollection($this->productRepository->showProductPage($id));
    }
    public function getAll()
    {
        return new ProductCollection($this->productRepository->get());
    }
}
