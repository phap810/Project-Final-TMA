<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\ProductRepository;
use App\Http\Resources\product\ProductResource;
use App\Http\Resources\product\ProductSizeResource;
use App\Http\Resources\product\ProductColorResource;
use App\Http\Requests\CartRequest;

class CartController extends Controller
{
    private $productRepository;
    public $items = [];
    public $total_quantity = 0;
    public $total_price    = 0;

    public function __construct(ProductRepository $productRepository)
    {
        // $this->items = session('cart') ? session('cart') : [];
        // $this->total_price = $this->get_total_price();
        // $this->total_quantity = $this->get_total_quantity();
        $this->productRepository = $productRepository;
    }
    public function add(Request $request, CartRequest $cartRequest)
    {
        $product = $this->productRepository->showCart($cartRequest);
        // $request->session()->forget('cart');
        // $carttest = $request->session()->get('cart');
        // dd( $carttest);
        
        if(!empty($product)){
            $this->items = $request->session()->get('cart', []);
            $quantity = $cartRequest->quantity ?? 1;
            $new = [                
                'id'        => $product['product']['id'],
                'name'      => $product['product']['name'],
                'price'     => $product['product']['export_price'] ? $product['product']['export_price'] : $product['product']['import_price'],
                'img'       => $product['product']['img'],
                'size_id'   => $product['size']['id'],
                'size'      => $product['size']['size'],
                'color_id'  => $product['color']['id'],
                'color'     => $product['color']['color'],
                'quantity'  => $quantity,
                ];
                
            if(isset($this->items[$product['product']['id']]) && $this->items[$product['product']['id']]['size_id'] == $cartRequest->size_id && $this->items[$product['product']['id']]['color_id'] == $cartRequest->color_id){
                $this->items[$product['product']['id']]['quantity'] += $quantity;
                $new['quantity'] = $this->items[$product['product']['id']]['quantity'];
            }
            else{
                $this->items[$product['product']['id']] = $new;
            }
            $request->session()->put('cart', $this->items);
            return response()->json([
                'status' => true,
                'code'   => Response::HTTP_OK,
                'data'  => [
                    'items' => $new,
                    'total_price' => $this->total_price,
                    'total_quantity' => $this->total_quantity
                ],
                'message' => 'Add to cart successfully',
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'code'   => 500,
                'error' => 'Add to cart failed',
            ], 200);
        }
        
    }

    public function update($id, Request $request)
    {
        
        if(isset($this->items[$id])){
            $this->items[$id]['quantity'] += $quantity;
            $new['quantity'] = $this->items[$product['product']['id']]['quantity'];
        }
        $request->session()->put('cart', $this->items);
        $carttest = $request->session()->get('cart');
        dd( $carttest);
        
    }

    public function remove($id)
    {
        if(isset($this->items[$id])){
            unset($this->items[$id]);
        }
        // else{
        //     return response()->json([
        //         'status' => false,
        //         'code'   => 500,
        //         'messager' => 'This item does not exist',
        //     ], 500);
        // }
        session(['cart'=> $this->items]);
        return response()->json([
            'status' => true,
            'code'   => 200,
            'messager' => 'Item deleted successfully',
        ], 200);
        
    }

    public function clear(Request $request)
    {
        if(empty($request->session()->get('cart'))){
            return response()->json([
                'status' => false,
                'code'   => 500,
                'messager' => 'Your cart is empty',
            ], 500);
        }else{
            $request->session()->forget('cart');
            if(empty($request->session()->get('cart'))){
                return response()->json([
                    'status' => true,
                    'code'   => 200,
                    'messager' => 'Cart deleted successfully',
                ], 200);
            }
        }
    }

    private function get_total_price()
    {
        $totalPrice = 0;
        foreach($this->items as $new){
            $totalPrice += $new['price']*$new['quantity'];
        }
        return $totalPrice;
    }

    private function get_total_quantity()
    {
        $totalQuantity = 0;
        foreach($this->items as $new){
            $totalQuantity += $new['quantity'];
        }
        return $totalQuantity;
    }
}
