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
            'total'       => $cart['total_price'],
            'payment'     => $inputs['payment'],
            'dateorder'   => date('Y-m-d'),
            'note'        => $inputs['note'],
            'status'      => 1,
        ]);
    }
    public function storeBillDetail($bill_id, $PSCdata, $rowCart)
    {
        return BillDetail::create([
            'id_bill'               => $bill_id,
            'id_product_size_color' => $PSCdata[0]['id'],
            'amount'                => $rowCart['quantity'],
            'price'                 => $rowCart['price']
        ]);
    }
    public function showPSC($rowCart)
    {
        return ProductSizeColor::
        where('product_id', $rowCart['id'])
        ->where('size_id', $rowCart['size_id'])
        ->where('color_id', $rowCart['color_id'])->get();
    }
}
