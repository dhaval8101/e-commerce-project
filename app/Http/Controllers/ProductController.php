<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Traits\SearchableTrait;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\SubCategory;

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
            'quantity'        => 'required|numeric',
            'category_id'     => [
                'required', 'exists:categories,id',
                function ($attribute, $value, $fail) {
                    if (!Category::where('id', $value)->exists()) {
                        $fail('Invalid category_id.');
                    }
                }
            ],
            'sub_category_id'    => [
                'required', 'exists:sub_categories,id',
                function ($attribute, $value, $fail) {
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
        return successResponse($product, 'Product  create Successfully ');
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return successResponse($product, 'Product details ');
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
            'sub_category_id' => 'required|exists:sub_categories,id',
            'quantity'        => 'required|numeric',
            'category_id'     => [
                'required', 'exists:categories,id',
                function ($attribute, $value, $fail) {
                    if (!Category::where('id', $value)->exists()) {
                        $fail('Invalid category_id.');
                    }
                }
            ],
            'sub_category_id'    => [
                'required', 'exists:sub_categories,id',
                function ($attribute, $value, $fail) {
                    if (!SubCategory::where('id', $value)->exists()) {
                        $fail('Invalid sub_category_id.');
                    }
                }
            ]
        ]);
        if ($validation->fails()) {
            return errorResponse($validation->errors()->first());
        }
        $product = Product::findOrFail($id);
        $product->update($request->all(['name', 'price', 'description', 'quantity', 'category_id', 'sub_category_id']));
        return successResponse($product, 'product update Successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $product = Product::findOrFail($id)->delete();
        return successResponse($product, 'Product delete Successfully');
    }
    //search and pagination 
    public function index(Request $request)
    {
        // Validate input parameters
        $this->validate(request(), [
            'category_id'     => 'nullable|integer',
            'sub_category_id' => 'nullable|integer',
            'search'          => 'nullable|string',
            'per_page'        => 'nullable|integer',
            'page'            => 'nullable|integer'
        ]);
        $product  = Product::query()->orderBy('id', 'desc');
        // Define fields that can be searched
        $searchable_fields = ['sub_category_id', 'category_id', 'name'];
        return $this->list($request, $product, $searchable_fields);
    }
}