<?php

namespace App\Repositories;

use App\Models\Bill;
use App\Models\BillDetail;
use App\Models\ProductSizeColor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\UserUnauthorizedException;
use Config;

class BillRepository
{
    public function store($inputs, $customer_id, $cart)
    {
        return Bill::create([
            'customer_id' => $customer_id,
            'total'       => '30000',//$cart->total
            'payment'     => $inputs['payment'],
            'dateorder'   => date('Y-m-d'),
            'note'        => $inputs['note'],
            'status'      => 1,
        ]);
    }
    public function storeBillDetail($bill_id, $cart, $idPSC)
    {
        return BillDetail::create([
            'id_bill' => $bill_id,
            'id_product' => $cart['id'],
            'id_product_size_color' =>  $idPSC,
            'amount' => $cart['quantity'],
            'price' => $cart['price']/$value['quantity']
        ]);
    }
    public function showPSC($cart)
    {
        return ProductSizeColor::where('product_id', $cart['id'])
            ->where('size_id', $cart['size_id'])
            ->where('color_id', $cart['color_id'])
            ->get();
    }
}
