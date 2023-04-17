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
            'name'        => 'required',
            'category_id' => [
                'required', 'exists:categories,id',
                function ($attribute, $value, $fail) {
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
        return successResponse($subcategory, 'Subcategory create Successfully');
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
            $subcategory = SubCategory::findOrFail($id);
            return successResponse($subcategory, 'Subcategory details ');
    } 
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'name'        => 'required',
            'category_id' => [
                'required', 'exists:categories,id',
                function ($attribute, $value, $fail) {
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
        return successResponse($subcategory, 'Subcategory update Successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $subcategory = SubCategory::findOrFail($id);
        $subcategory->delete();
        return successResponse($subcategory, 'Subcategory delete Successfully ');
    }

    //search and pagination 
    public function index(Request $request)
    {
        // Validate input parameters
        $this->validate(request(), [
            'category_id' => 'nullable|integer',
            'search'      => 'nullable|string',
            'per_page'    => 'nullable|integer',
            'page'        => 'nullable|integer'
        ]);
        $subcategory = SubCategory::query()->orderBy('id', 'desc');
        $searchable_fields = ['category_id','name'];

        return $this->list($request, $subcategory, $searchable_fields);
    }    
}