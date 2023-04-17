<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Traits\SearchableTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryController extends Controller
{
    use SearchableTrait;
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name'  => 'required',
        ]);
        if ($validation->fails()) {
            return errorResponse($validation->errors()->first());
        }
        $category = new Category();
        $category->name = $request->name;
        $category->save();
        return successResponse($category, 'Category create Successfully');
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
            $category = Category::findOrFail($id);
            return successResponse($category, 'Category details ');
    } 
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'name'  => 'required',
        ]);
        if ($validation->fails()) {
            return errorResponse($validation->errors()->first());
        }
        $category = Category::findOrFail($id);
        $category->update($request->all(['name']));
        return successResponse($category, 'Category update successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return successResponse($category,'Category delete successfully');
    }
    //search and pagination 
    public function index(Request $request)
    {
        // Validate input parameters
        $this->validate(request(), [
            'search'   => 'nullable|string',
            'per_page' => 'nullable|integer',
            'page'     => 'nullable|integer'
        ]);
        $category = Category::query()->orderBy('id', 'desc');
        $searchable_fields = ['name'];

        return $this->list($request,$category, $searchable_fields);
    }

}