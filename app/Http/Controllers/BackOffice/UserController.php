<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\BackOffice\UserIndexRequest;
use App\Http\Requests\BackOffice\UserStoreRequest;
use App\Http\Requests\BackOffice\UserUpdateRequest;
use App\Models\User;
use App\Services\UserService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(UserIndexRequest $request)
    {
        $query = User::query()
            ->whereNull('deleted_at');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('employee_number', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $limit = (int) $request->query('limit', 10);

        $paginator = $query
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        return ApiResponse::success(
            $paginator->items(),
            'Successful',
            200,
            [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ]
        );
    }

    public function store(UserStoreRequest $request)
    {
        $user = UserService::create($request->validated());

        return ApiResponse::success(
            [
                'id' => $user->id,
                'employee_number' => $user->employee_number,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
            ],
            'Successful',
            201
        );
    }

    public function show($id)
    {
        $user = User::whereNull('deleted_at')->find($id);

        if (!$user) {
            return ApiResponse::error(
                'User not found',
                [
                    'user' => 'error.not_found',
                ],
                404
            );
        }

        return ApiResponse::success(
            [
                'id' => $user->id,
                'employee_number' => $user->employee_number,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
            'Successful'
        );
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::whereNull('deleted_at')->find($id);
        if (!$user) {
            return ApiResponse::error(
                'User not found',
                [
                    'user' => 'error.not_found',
                ],
                404
            );
        }

        $user = UserService::update($user, $request->validated());

        return ApiResponse::success(
            [
                'id' => $user->id,
                'employee_number' => $user->employee_number,
                'name' => $user->name,
                'email' => $user->email,
                'updated_at' => $user->updated_at,
            ],
            'Successful'
        );
    }

    public function destroy($id)
    {
        $user = User::whereNull('deleted_at')->find($id);

        if (!$user) {
            return ApiResponse::error(
                'User not found',
                [
                    'user' => 'error.not_found',
                ],
                404
            );
        }

        UserService::delete($user);

        return ApiResponse::success(
            null,
            'Successful'
        );
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->find($id);

        if (!$user) {
            return ApiResponse::error(
                'User not found or not deleted',
                [
                    'user' => 'error.not_found',
                ],
                404
            );
        }

        $user = UserService::restore($user);

        return ApiResponse::success(
            [
                'id' => $user->id,
                'employee_number' => $user->employee_number,
                'name' => $user->name,
                'email' => $user->email,
                'restored_at' => now(),
            ],
            'Successful'
        );
    }
}
