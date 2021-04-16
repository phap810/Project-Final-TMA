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
use App\Libraries\CartSession;

class BillController extends Controller
{
    private $billRepository;
    private $customerRepository;

    public function __construct(BillRepository $billRepository, CustomerRepository $customerRepository)
    {
        $this->billRepository = $billRepository;
        $this->customerRepository = $customerRepository;
    }
    public function store(CustomerRequest $customerRequest, BillRequest $billRequest)
    {
        $items = session('cart');
        // $a = $items['229125141']['id'];
        // dd($a);
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
            $bill = new BaseResource($this->billRepository->store($billRequest->storeFilter(), $customer->id, $cart));
            if($bill == true){
                foreach($cart['items'] as $rowCart){
                    $PSCdata = $this->billRepository->showPSC($rowCart);
                    $this->billRepository->storeBillDetail($bill->id, $PSCdata, $rowCart);   
                }
                return $bill;
            }
        }
    }
}
