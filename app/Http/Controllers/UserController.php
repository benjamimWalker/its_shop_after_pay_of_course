<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\PaginationState;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;

#[Authenticated]
#[Group(name: 'Users', description: 'Users API')]
class UserController extends Controller
{
    #[QueryParam(name: 'filter_column', description: 'Column to filter by', required: false)]
    #[QueryParam(name: 'filter_value', description: 'Value to filter by', required: false)]
    #[QueryParam(name: 'sort_column', description: 'Column to sort by', required: false)]
    #[QueryParam(name: 'sort_direction', description: 'Direction to sort by', required: false)]
    #[QueryParam(name: 'per_page', description: 'Number of results per page', required: false)]
    public function index(Request $request): JsonResponse
    {
        return response()->json(
            User::with('stores')->when($request->filter_column, fn($query) => $query->where($request->filter_column, 'like', ('%' . $request->filter_value ?? '') . '%'))
                ->orderBy($request->sort_column ?? 'id', $request->sort_direction ?? 'asc')
                ->paginate($request->per_page)
        );
    }

    public function show(User $user): JsonResponse
    {
        $user->load('stores');
        return response()->json($user);
    }

    public function store(UserRequest $request): JsonResponse
    {
        $request->merge(['password' => bcrypt($request->password)]);
        return response()->json(User::create($request->validated()), 201);
    }
    public function update(User $user, UserRequest $request): Response
    {
        $user->update($request->validated());

        return response()->noContent();
    }

    public function destroy(User $user): Response
    {
        $user->delete();

        return response()->noContent();
    }

    public function destroyAll(Request $request): Response
    {
        if ($request->ids) {
            User::whereIn('id', explode(',', $request->ids))->delete();
        } else {
            User::truncate();
        }

        return response()->noContent();
    }
}
