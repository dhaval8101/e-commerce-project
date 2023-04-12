<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
USE App\Models\User;
class CartController extends Controller
{   
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'price'           => 'required|numeric',
            'user_id'         => 'required|exists:users,id',
            'quantity'        => 'required|numeric',
            'product_id'      => [
                'required',
                function($attribute, $value, $fail) {
                    if (!Product::where('id', $value)->exists()) {
                        $fail('Invalid product_id.');
                    
                     } }
            ],
        ]);
        
        if ($validation->fails()) {
            return errorResponse($validation->errors()->first());
        } 
        $cart = new Cart();
        $cart->product_id= $request->product_id;
        $cart->user_id  = $request->user_id;
        $cart->quantity = $request->quantity;
        $cart->price    = $request->price;
        $cart->save();
        return successResponse($cart, 'Cart create successfully');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {   
        $cart = Cart::find($id);
        if (!$cart) {
            return errorResponse('Cart not found');
        }
        return successResponse($cart, 'Cart show successfully');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'price'           => 'required|numeric',
            'user_id'         => 'required|exists:users,id',
            'quantity'        => 'required|numeric',
            'product_id'     => [
                'required',
                function($attribute, $value, $fail) {
                    if (!Product::where('id', $value)->exists()) {
                        $fail('Invalid product_id.');
                    
                     } 
                    }
            ],
          ]);
        
        if ($validation->fails()) {
            return errorResponse($validation->errors()->first());
        } 
        $cart = Cart::find($id);
        if (!$cart) {
            return errorResponse('cart not found');
        }
        $cart->update($request->all(['product_id', 'user_id', 'quantity', 'price']));
        return successResponse($cart, 'Cart update successfully');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $cart = Cart::where('id', $id)->first();

        if ($cart) {
            $cart->delete();
            return successResponse('cart delete successfully');
        }
        return errorResponse('cart Already Deleted');
    }
}