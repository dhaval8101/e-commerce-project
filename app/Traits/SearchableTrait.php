<?php

namespace App\Traits;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

trait SearchableTrait
{
    public function list(Request $request, $model, $searchable_fields = [])
    {
        $query = $model::query();
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->has('sub_category_id')) {
            $query->where('sub_category_id', $request->sub_category_id);
        }     
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search, $searchable_fields) {
                foreach ($searchable_fields as $searchable_field) {
                    $q->orWhere($searchable_field, 'like', '%' . $search . '%');
                }
            });
        }
        $perPage = $request->per_page ?? 10;
        $data = $query->paginate($perPage);
        return response()->json([
            'success' => true,
            'data'    => $data->items(),
            'total'   => $data->total()
        ]);
    }
}