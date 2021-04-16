<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Size;
use App\Models\ProductSizeColor;
use App\Models\Color;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\UserUnauthorizedException;
use Config;

class ProductRepository
{
    //search
    public function search($inputs)
    {
        return Product::with('supplier')
        ->join('supplier', 'product.supplier_id', '=', 'supplier.id')
        ->join('category', 'product.category_id', '=', 'category.id')
        ->select('product.id as id', 'product.name as name', 
               'product.img as img', 'product.note as note',
               'product.import_price as import_price',
               'product.export_price as export_price',
               'product.amount as amount',
               'product.sale as sale',
               'product.status as status',
               'supplier.name as supplier_id',
               'category.name as category_id')
        ->when(isset($inputs['id']), function ($query) use ($inputs) {
            return $query->where('product.id', $inputs['id']);
        })
        ->when(isset($inputs['status']), function ($query) use ($inputs) {
            return $query->where('product.status', $inputs['status']);
        })
        ->when(isset($inputs['name']), function ($query) use ($inputs) {
            return $query->where('product.name', 'LIKE', '%' . $inputs['name'] . '%');
        })
        ->orderBy('product.sale', 'desc')
        ->paginate(10);
    }
    //show
    public function show($id)
    {
        return Product::findOrFail($id);
    }
    //destroy
    public function destroy($id)
    {
        ProductSizeColor::where('product_id', $id)
            ->delete();
        return Product::findOrFail($id)
            ->delete();
    }
    //store
    public function store($inputs, $newNamefile)
    {
        return Product::create([
            'name'          => $inputs['name'],
            'img'           => $newNamefile,
            'note'          => $inputs['note'],
            'import_price'  => $inputs['import_price'],
            'export_price'  => $inputs['export_price'],
            'sale'          => $inputs['sale'],
            'supplier_id'   => $inputs['supplier_id'],
            'category_id'   => $inputs['category_id'],
            'status'        => 1
        ]);
    }
    //storePSC
    public function storePSC($data, $product_id)
    {
        return ProductSizeColor::create([
            'product_id' => $product_id,
            'size_id'    => $data['size_id'],
            'color_id'   => $data['color_id'],
            'amount'     => $data['amount']
        ]);
    }
    //SUM
    public function sum($id)
    {
        return ProductSizeColor::where('product_id', $id)->sum('amount');
    }
    //amount
    public function amount($product_id, $totalAmount)
    {
        return Product::where('id', $product_id)
            ->update([
                'amount' => $totalAmount
            ]);
    }
    //update
    public function update($inputs, $id)
    {
        return Product::findOrFail($id)
            ->update([
                'name'          => $inputs['name'],
                'img'           => $inputs['img'],
                'note'          => $inputs['note'],
                'import_price'  => $inputs['import_price'],
                'export_price'  => $inputs['export_price'],
                'sale'          => $inputs['sale'],
                'supplier_id'   => $inputs['supplier_id'],
                'category_id'   => $inputs['category_id'],
                'status'        => $inputs['status']
            ]);
    }
    //updateImg
    public function updateImg($inputs, $newNamefile, $id)
    {
        return Product::findOrFail($id)
            ->update([
                'name'          => $inputs['name'],
                'img'           => $newNamefile,
                'note'          => $inputs['note'],
                'import_price'  => $inputs['import_price'],
                'export_price'  => $inputs['export_price'],
                'sale'          => $inputs['sale'],
                'supplier_id'   => $inputs['supplier_id'],
                'category_id'   => $inputs['category_id'],
                'status'        => $inputs['status']
            ]);
    }
    //showPSC
    public function showPSC($id)
    {
        return ProductSizeColor::where('product_id', $id)->get();
    }
    public function showProductSizeColor($psc)
    {
        return ProductSizeColor::where('id', $psc)->get();
    }
    //updatePSC
    public function updatePSC($data)
    {
        return ProductSizeColor::findOrFail($data['id'])
            ->update([
                'size_id'    => $data['size_id'],
                'color_id'   => $data['color_id'],
                'amount'     => $data['amount']
        ]);
    }

    //
    public function category($id)
    {
        return Product::
        join('supplier', 'product.supplier_id', '=', 'supplier.id')
        ->join('category', 'product.category_id', '=', 'category.id')
        ->select('product.id as id', 'product.name as name', 
                    'product.img as img', 'product.note as note',
                    'product.import_price as import_price',
                    'product.export_price as export_price',
                    'product.amount as amount',
                    'product.sale as sale',
                    'product.status as status',
                    'supplier.name as supplier_id',
                    'category.name as category_id')
        ->where('product.category_id', '=', $id)
        ->orderBy('product.name', 'desc')
        ->paginate(10);
    }

    public function getData($product_id)
    {
        return Product::where('id', $product_id)->get();
    }

    public function showSize($id)
    {
        return Size::findOrFail($id);
    }
    public function showColor($id)
    {
        return Color::findOrFail($id);
    }

    public function showCart($inputs)
    {
        return [
            'product'   => Product::findOrFail($inputs['id']),
            'size'      => Size::findOrFail($inputs['size_id']),
            'color'     => Color::findOrFail($inputs['color_id'])
        ];
    }

}