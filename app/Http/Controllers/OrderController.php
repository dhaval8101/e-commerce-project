<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Traits\SearchableTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class OrderController extends Controller
{
    use SearchableTrait;
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
                'quantity'       => 'required',
                'price'          => 'required',
                'payment_method' => 'required',
                'status'         => 'required',
                'phone'          => 'required',
                'address'        => 'required',
                'city'           => 'required',
                'pin_code'       => 'required',
                'cart_id'        => 'required|exists:carts,id',
                'user_id'        => 'required|exists:users,id',
                'product_id'     => ['required','exists:products,id',
            function($attribute, $value, $fail) {
                if (!Product::where('id', $value)->exists()) {
                    $fail('Invalid product_id.');
                }
            }
        ]
    ]);
          if ($validation->fails()) {
            return errorResponse($validation->errors()->first());
        }  
        $order = new Order();
        $order -> cart_id=$request->cart_id;
        $order -> product_id = $request->product_id;
        $order -> user_id = $request->user_id;
        $order -> quantity =$request->quantity;
        $order -> price = $request->price;
        $order -> payment_method = $request->payment_method;
        $order -> status=$request->status;
        $order -> phone = $request->phone;
        $order -> address = $request->address;
        $order -> city=$request->city;
        $order -> pin_code = $request->pin_code;
        $order->save();
        return successResponse($order, 'order create successfully');
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
            $order = Order::findOrFail($id);
            return successResponse($order, 'order details ');
    } 
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'quantity'       => 'required',
            'price'          => 'required',
            'payment_method' => 'required',
            'status'         => 'required',
            'phone'          => 'required',
            'address'        => 'required',
            'city'           => 'required',
            'pin_code'       => 'required',
            'cart_id'        => 'required|exists:carts,id',
            'user_id'        => 'required|exists:users,id',
            'product_id'     => ['required','exists:products,id',
            function($attribute, $value, $fail) {
                if (!Product::where('id', $value)->exists()) {
                    $fail('Invalid product_id.');
                }
            }
        ]
    ]);
    if ($validation->fails()) {
        return errorResponse($validation->errors()->first());
    }  
        $order = Order::findOrFail($id);
        $order->update($request->all(['cart_id', 'product_id', 'user_id', 'quantity', 'price', 'payment_method', 'status', 'phone', 'address', 'city', 'pin_code']));
        return successResponse($order, 'order update successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return successResponse($order,'order delete successfully');
    }
    //search and pagination 
        public function index(Request $request)
        {
            $this->validate($request, [
                'search'   => 'nullable|string',
                'per_page' => 'nullable|integer',
                'page'     => 'nullable|integer'
            ]);
            $order = Order::query()->orderBy('id', 'desc');
            if (Auth::user()->role == 'user') {
                $user_id = Auth::user()->id;
                $order->where('user_id', $user_id);
            }
            $order = $this->list($request, $order, null);
            if ($order->isEmpty()) {
                return errorResponse('Data Not found');
            }
            return successResponse(' order details', $order);
        }
    }