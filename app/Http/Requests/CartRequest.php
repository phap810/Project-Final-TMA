<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        if ($this->isMethod('post')){
            return $this->storeRules();
        }elseif($this->isMethod('put')){
            return $this->updateRules();
        }
    }
    
    //add Cart
    public function storeRules(): array
    {
        return [
            'id' => 'integer',
            'size_id' => 'required',
            'color_id' => 'required',
            'quantity' => 'integer'
        ];
    }
    public function storeFilter()
    {
        return $this->only([
            'id',
            'size_id',
            'color_id',
            'quantity'
        ]);
    }

    //Update Category
    // public function updateRules(): array
    // {
    //     $id = $this->id;
    //     return [
    //         'name' => 'required||string|min:1|max:100|unique:category,name,'.$id,
    //         'status' => 'integer|max:2'
    //     ];
    // }
    // public function updateFilter()
    // {
    //     return $this->only([
    //         'name',
    //         'status'
    //     ]);
    // }
}
