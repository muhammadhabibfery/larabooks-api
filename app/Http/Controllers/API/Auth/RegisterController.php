<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\UserRequest;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(UserRequest $request): JsonResponse
    {
        $data = array_merge(
            $request->validated(),
            [
                'role' => 'CUSTOMER',
                'status' => 'ACTIVE',
                'password' => Hash::make($request->validated()['password'])
            ]
        );

        if (User::create($data)) return response()->json(['code' => 200, 'message' => 'Succes'], 200);

        return response()->json(['code' => 500, 'message' => 'failed to register'], 500);
    }
}
