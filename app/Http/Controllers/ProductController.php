<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Traits\SearchableTrait;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class ProductController extends Controller
{
    use SearchableTrait;
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name'            => 'required',
            'price'           => 'required|numeric',
            'description'     => 'required',
            'category_id'     => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'quantity'        => 'required|numeric',
            'category_id'     => [
                'required',
                function($attribute, $value, $fail) {
                    if (!Category::where('id', $value)->exists()) {
                        $fail('Invalid category_id.');
                    } elseif (Category::where('id', $value)->onlyTrashed()->exists()) {    
                     } }
            ],
            'sub_category_id'  => [
                'required',
                function($attribute, $value, $fail) {
                    if (!SubCategory::where('id', $value)->exists()) {
                        $fail('Invalid sub_category_id.');
                    }
                }
            ]
        ]);
        
        if ($validation->fails()) {
            return errorResponse($validation->errors()->first());
        }   
        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->sub_category_id = $request->sub_category_id;
        $product->save();
        return successResponse($product,'product  create  successfully');
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return errorResponse('product not found');
        }
        return successResponse($product, 'product show successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'name'            => 'required',
            'price'           => 'required|numeric',
            'description'     => 'required',
            'category_id'     => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'quantity'        => 'required|numeric',
            'category_id'     => [
                'required',
                function($attribute, $value, $fail) {
                    if (!Category::where('id', $value)->exists()) {
                        $fail('Invalid category_id.');
                    }
                     }
            ],
            'sub_category_id'  => [
                'required',
                function($attribute, $value, $fail) {
                    if (!SubCategory::where('id', $value)->exists()) {
                        $fail('Invalid sub_category_id.');
                    }
                }
            ]
        ]);
        
        if ($validation->fails()) {
            return errorResponse($validation->errors()->first());
        }   
        $product = Product::find($id);
        if (!$product) {
            return errorResponse('product not found');
        }

        $product->update($request->all(['name', 'price', 'description', 'quantity', 'category_id', 'sub_category_id']));
        return successResponse($product, 'product update successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $product = Product::where('id', $id)->first();
        if ($product) {
            $product->delete();
            return successResponse('product delete successfully');
        }
        return errorResponse('product Already Deleted');
    }
    //search and pagination 
    public function index(Request $request)
    {
        $this->ListingValidation();
        $query = Product::query();
        $searchable_fields = ['name'];
        $data = $this->serching($query, $searchable_fields);
        return response()->json([
            'success' => true,
            'data'    => $data['query']->get(),
            'total'   => $data['count']
        ]);
    }
}