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
        // $a = Session::get('cart');
        // dd($a);
        $cart = [
            'items' => [
                229 => [
                    "id" => 229,
                    "name"=> "Dép Nike",
                    "price"=> 100000,
                    "img"=> "1245428979.png",
                    "size_id"=> 125,
                    "size"=> 39,
                    "color_id"=> 141,
                    "color"=> "Xanh",
                    "quantity"=> 2
                ],
                228 => [
                    "id" => 228,
                    "name"=> "Giày thể thao nam",
                    "price"=> 30000,
                    "img"=> "118294873.png",
                    "size_id"=> 125,
                    "size"=> 39,
                    "color_id"=> 140,
                    "color"=> "Xám",
                    "quantity"=> 2
                ]
                ],
            'total_price' => 260000,
            'total_quantity' => 4
        ];
        // $productPSC = $this->billRepository->showPSC($cart);
        // $idPSC = $productPSC[0]['id'];
        $customer = new BaseResource($this->customerRepository->store($customerRequest->storeFilter()));
        $customer_id = $customer->id;
        if($customer ==true){
            $bill = new BaseResource($this->billRepository->store($billRequest->storeFilter(), $customer_id, $cart));
            $bill_id = $bill->id;
            if($bill == true){
                foreach($cart['items'] as $key => $value){
                    $billDetail = new BaseResource($this->billRepository->storeBillDetail($bill_id, $cart, $idPSC));   
                }
                return $bill;
            }
        }
    }
}
