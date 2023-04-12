<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Traits\SearchableTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Category;
class SubcategoryController extends Controller
{
    use SearchableTrait;
    /**
     * Store a newly created resource in storage.
     */
        public function store(Request $request)
        {
            $validation = Validator::make($request->all(), [
                'name' => 'required',
                'category_id' => [
                    'required',
                    function($attribute, $value, $fail) {
                        if (!Category::where('id', $value)->exists()) {
                            $fail('Invalid category_id.');
                        } 
                    }
                ]
            ]);    
            if ($validation->fails()) {
                return errorResponse($validation->errors()->first());
            } 
            $subcategory = new SubCategory();
            $subcategory->name = $request->name;
            $subcategory->category_id = $request->category_id;
            $subcategory->save();
            return successResponse($subcategory,'Subcategory create  successfully');
        } 
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $subcategory = SubCategory::findOrFail($id);
            return successResponse($subcategory, 'Subcategory show successfully');
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Subcategory not found'], 400);
        }
    }  
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'category_id' => [
                'required',
                function($attribute, $value, $fail) {
                    if (!Category::where('id', $value)->exists()) {
                        $fail('Invalid category_id.');
                    } 
                }
            ]
        ]);
         
        if ($validation->fails()) {
            return errorResponse($validation->errors()->first());
        } 
        $subcategory = SubCategory::find($id);
        if (!$subcategory) {
            return errorResponse('Subcategory not found');
        }
        $subcategory->update($request->all(['name', 'category_id']));
        return successResponse($subcategory,'Subcategory update successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $subcategory = SubCategory::where('id', $id)->first();
        if ($subcategory) {
            $subcategory->delete();
            return successResponse('Subcategory delete successfully');
        }
        return errorResponse('Subcategory Already Deleted');
    }
    //search and pagination 
    public function index(Request $request)
    {
        $this->ListingValidation();
        $query = SubCategory::query();
        $searchable_fields = ['name'];
        $data = $this->serching($query, $searchable_fields);
        return response()->json([
            'success' => true,
            'data'    => $data['query']->get(),
            'total'   => $data['count']
        ]);
    }
}