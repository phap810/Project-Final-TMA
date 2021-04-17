<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\BaseResource;
use App\Http\Resources\product\ProductSizeColorResource;
use App\Repositories\CustomerRepository;
use App\Repositories\BillRepository;
use App\Http\Requests\BillRequest;
use Illuminate\Support\Facades\Session;
use App\Http\Resources\bill\BillResource;
use App\Http\Resources\bill\BillDetailResource;
use App\Http\Resources\bill\BillDetailCollection;

class BillController
{
    private $billRepository;
    private $customerRepository;

    public function __construct(BillRepository $billRepository, CustomerRepository $customerRepository, Request $request)
    {
        $this->billRepository = $billRepository;
        $this->customerRepository = $customerRepository;
    }
    public function store(CustomerRequest $customerRequest, BillRequest $billRequest)
    {
        $items = session('cart');
        $totalPrice      = 0;
        $totalQuantity   = 0;
        foreach($items as $rowCart){
            $totalPrice      += $rowCart['price']*$rowCart['quantity'];
            $totalQuantity   += $rowCart['quantity'];
        }
        $cart = [
            'items' => $items,
            'total_price'=> $totalPrice,
            'total_quantity' => $totalQuantity
        ];
        $customer = new BaseResource($this->customerRepository->store($customerRequest->storeFilter()));
        if($customer ==true){
            $bill = new BillResource($this->billRepository->store($billRequest->storeFilter(), $customer->id, $cart));
            if($bill == true){
                foreach($cart['items'] as $rowCart){
                    $PSCdata = $this->billRepository->showPSC($rowCart);
                    $billDetail = new BaseResource($this->billRepository->storeBillDetail($bill->id, $PSCdata, $rowCart));   
                }
                $dataBillDetail = new BillDetailCollection($this->billRepository->getBillDetail($bill->id));
                // unset($items[$id]);
                // $request->session()->put('cart', $items);
                return [$bill, $dataBillDetail];
            }
                
       }
    }
    public function update($id)
    {
        $bill = $this->billRepository->show($id);
        if($bill->status == 1){
            return new BaseResource($this->billRepository->update($id));
        }else{
            return new BaseResource($this->billRepository->updateStatus($id));
        } 
    }
    public function destroy($id, Request $request)
    {
        $bill = $this->billRepository->show($id);
        $destroy = new BaseResource($this->billRepository->destroy($id, $request->session()->put('bill', $bill->customer_id)));
        $request->session()->forget('bill');
        return $destroy;
    }
}
