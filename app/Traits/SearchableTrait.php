<?php

namespace App\Traits;
use Illuminate\Http\Request;

trait SearchableTrait
{
    public function ListingValidation()
    {
        $this->validate(request(), [
            'per_page'  => 'nullable|integer',
            'page'      => 'nullable|integer',
            'search'    => 'nullable|string'
        ]);
    }
    public function list(Request $request, $model, $searchable_fields = [])
    {
        // $query = $model::query();
        if ($request->has('category_id')) {
            $model->where('category_id', $request->category_id);
        }
        if ($request->has('sub_category_id')) {
            $model->where('sub_category_id', $request->sub_category_id);
        }
        if ($request->has('search')) {
            $search = $request->search;
            $model->where(function ($q) use ($search, $searchable_fields) {
                foreach ($searchable_fields as $searchable_field) {
                    $q->orWhere($searchable_field, 'like', '%' . $search . '%');
                }
            });
        }
        $perPage = $request->per_page ?? 10;
        $data = $model->paginate($perPage);
        return response()->json([
            'success' => true,
            'data'    => $data->items(),
            'total'   => $data->total()
        ]);
    }
}