<?php

namespace App\Libraries;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class CartSession
{
    public $items = [];
    public $total_quantity = 0;
    public $total_price    = 0;

    public function __construct()
    {
        $this->items = session('cart') ? session('cart') : [];
        $this->total_price = $this->get_total_price();
        $this->total_quantity = $this->get_total_quantity();
    }
 
    public function add($inputs, $product, $size, $color, Request $request)
    {
        $items = $request->session()->get('cart', []);
        dd($items);
        $quantity = $inputs->quantity ?? 1;
        $new = [                
            'id'        => $product->id,
            'name'      => $product->name,
            'price'     => $product->export_price ? $product->export_price : $product->import_price,
            'img'       => $product->img,
            'size_id'   => $size->id,
            'size'      => $size->size,
            'color_id'  => $color->id,
            'color'     => $color->color,
            'quantity'  => $inputs->quantity,
        ];    
            if(isset($items[$product->id])){
                $this->items[$product->id]['quantity'] += $quantity;
                $new['quantity'] = $this->items[$product->id]['quantity'];
            }
            else{
                
                $this->items[$product->id] = $new;
            }
            $request->session()->put('cart', $this->items);
        return response()->json([
            'status' => true,
            'code'   => Response::HTTP_OK,
            'data'  => $new,
            'message' => 'Success',
        ], 200);
        //
        // $item = [
            
        //     'id'        => $product->id,
        //     'name'      => $product->name,
        //     'price'     => $product->export_price ? $product->export_price : $product->import_price,
        //     'img'       => $product->img,
        //     'size_id'   => $size->id,
        //     'size'      => $size,
        //     'color_id'  => $color->id,
        //     'color'     => $color,
        //     'quantity'  => $quantity,//$inputs->qty,
        // ];
        //     if(isset($this->items[$product->id]) && isset($this->items[$size->id]) && isset($this->items[$color->id])){
        //         $this->items[$product->id]['quantity'] += $quantity;
        //     }else{
        //         $this->items[$product->id] = $item;
        //     }
        //     session(['cart'=> $this->items]);
        //     $cart = session()->put('cart', $this->items[$product->id]);
        //     // $cart = session('cart', $this->items[$product->id]);
        // return response()->json([
        //     'status' => true,
        //     'code'   => Response::HTTP_OK,
        //     'data'  => $cart,
        //     'message' => 'Success',
        // ], 200);
    }

    public function show()
    {
        $cart = session()->get('cart');
        return response()->json([
            'cart' => $cart
        ], 200);
    }

    public function remove($id)
    {
        if(isset($this->items[$id])){
            unset($this->items[$product->id]);
        }
        session(['cart'=> $this->items]);
    }

    public function update($id, $quantity = 1)
    {
        if(isset($this->items[$id])){
            $this->items[$product->id]['quantity'] = $quantity;
        }
        session(['cart'=> $this->items]);
    }

    public function clear()
    {
        session(['cart'=> '']);
    }

    private function get_total_price()
    {
        $totalPrice = 0;
        foreach($this->items as $item){
            $totalPrice += $item['price']*$item['quantity'];
        }
        return $totalPrice;
    }

    private function get_total_quantity()
    {
        $totalQuantity = 0;
        foreach($this->items as $item){
            $totalQuantity += $item['quantity'];
        }
        return $totalQuantity;
    }
}
