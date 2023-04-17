<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\SearchableTrait;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    use SearchableTrait;
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'price'           => 'required|numeric',
            'user_id'         => 'required|exists:users,id',
            'quantity'        => 'required|numeric',
            'product_id'   => ['required','exists:products,id',
        ],
    ]);
        if ($validation->fails()) {
            return errorResponse($validation->errors()->first());
        }
        $cart = new Cart();
        $cart->product_id = $request->product_id;
        $cart->user_id  = $request->user_id;
        $cart->quantity = $request->quantity;
        $cart->price    = $request->price;
        $cart->save();
        return successResponse($cart, 'Cart create successfully');
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cart = Cart::findOrFail($id);
        return successResponse($cart, 'Cart details ');
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
            'product_id'   => ['required','exists:products,id',
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
        $cart = Cart::findOrFail($id);
        $cart->delete();
        return successResponse($cart, 'Cart delete successfully');
    }
    //search and pagination 
    public function index(Request $request)
    {
        $this->validate($request, [
            'search'   => 'nullable|string',
            'per_page' => 'nullable|integer',
            'page'     => 'nullable|integer'
        ]);
            $cart = Cart::query()->orderBy('id', 'desc');
        if (Auth::user()->role == 'user') {
            $user_id = Auth::user()->id;
            $cart->where('user_id', $user_id);
        }
        $cart = $this->list($request, $cart, null);
        if ($cart->isEmpty()) {
            return errorResponse('Data Not found');
        }
        return successResponse('user cart details', $cart);
    }
    
}