<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Models\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;

#[Authenticated]
#[Group(name: 'Stores', description: 'Stores API')]
class StoreController extends Controller
{
    #[QueryParam(name: 'filter_column', description: 'Column to filter by', required: false)]
    #[QueryParam(name: 'filter_value', description: 'Value to filter by', required: false)]
    #[QueryParam(name: 'sort_column', description: 'Column to sort by', required: false)]
    #[QueryParam(name: 'sort_direction', description: 'Direction to sort by', required: false)]
    #[QueryParam(name: 'per_page', description: 'Number of results per page', required: false)]
    public function index(Request $request): JsonResponse
    {
        return response()->json(
            Store::with('owner')->when($request->filter_column, fn($query) => $query->where($request->filter_column, 'like', ('%' . $request->filter_value ?? '') . '%'))
                ->orderBy($request->sort_column ?? 'id', $request->sort_direction ?? 'asc')
                ->paginate($request->per_page)
        );
    }

    public function show(Store $store): JsonResponse
    {
        $store->load('owner');
        return response()->json($store);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        return response()->json(Store::create($request->validated()), 201);
    }

    public function update(Store $store, StoreRequest $request): Response
    {
        $store->update($request->validated());

        return response()->noContent();
    }

    public function destroy(Store $store): Response
    {
        $store->delete();

        return response()->noContent();
    }

    public function destroyAll(Request $request): Response
    {
        if ($request->ids) {
            Store::whereIn('id', explode(',', $request->ids))->delete();
        } else {
            Store::truncate();
        }

        return response()->noContent();
    }
}
