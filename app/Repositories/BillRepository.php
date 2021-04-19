<?php

namespace App\Repositories;

use App\Models\Bill;
use App\Models\BillDetail;
use App\Models\Customer;
use App\Models\ProductSizeColor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\UserUnauthorizedException;
use Config;

class BillRepository
{
    public function search($inputs)
    {
        return Bill::join('customer', 'bill.customer_id', '=', 'customer.id')
        ->select('bill.id as id', 'customer.name as customer_id',
                'bill.total as total', 'bill.payment as payment',
                'bill.dateorder as dateorder', 'bill.note as note', 'bill.status as status')
        ->when(isset($inputs['bill.id']), function ($query) use ($inputs) {
            return $query->where('bill.id', $inputs['id']);
        })
        ->when(isset($inputs['bill.dateorder']), function ($query) use ($inputs) {
            return $query->where('bill.dateorder', 'LIKE', '%' . $inputs['dateorder'] . '%');
        })
        ->orderBy('bill.id', 'desc')
        ->paginate(10);
    }
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
    public function getBillDetail($bill_id)
    {
        return BillDetail::
        when(isset($bill_id), function ($query) use ($bill_id) {
            return $query->where('id_bill', $bill_id);
        })
        ->orderBy('id', 'desc')
        ->paginate();
    }
    public function show($id)
    {
        return Bill::findOrFail($id);
    }
    public function update($id)
    {
        return Bill::find($id)
            ->update(['status' => 2]);
    }
    public function updateStatus($id)
    {
        return Bill::find($id)
            ->update(['status' => 3]);
    }
    public function destroy($id, $bill)
    {
        BillDetail::where('id_bill', $id)
            ->delete();
        Bill::findOrFail($id)
            ->delete();
        return Customer::findOrFail(session('bill'))
            ->delete();
    }
}
