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
        return successResponse($category, 'Category create successfully');
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);
            return successResponse($category, 'Category show successfully');
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Category not found'], 400);
        }
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
        $category = Category::find($id);
        if (!$category) {
            return errorResponse('Category not found');
        }
        $category->update($request->all(['name']));
        return successResponse($category, 'Category update successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $category = Category::where('id', $id)->first();
        if ($category) {
            $category->delete();
            return successResponse('Category delete successfully');
        }
        return errorResponse('Category Already Deleted');
    }
    //search and pagination 
    public function index()
    {
        $this->ListingValidation();
        $query = Category::query();
        $searchable_fields = ['name'];
        $data = $this->serching($query, $searchable_fields);
        return response()->json([
            'success' => true,
            'data'    => $data['query']->get(),
            'total'   => $data['count']
        ]);
    }
}