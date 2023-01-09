<?php

namespace App\Http\Controllers\API\Auth;

use App\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\UserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{

    /**
     * Handle login user/customer
     *
     * @param  \App\Http\Requests\API\UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(UserRequest $request): JsonResponse
    {
        $user = $this->getUserByEmail($request->validated()['email']);

        if (!$user || !Hash::check($request->validated()['password'], $user->password))
            throw ValidationException::withMessages([
                'email' => 'The credentials does not match'
            ]);

        $user = (new UserResource($user))
            ->additional(['token' => $user->createToken('customerToken', $user->role)->plainTextToken])
            ->response()
            ->getData(true);

        return $this->wrapResponse(200, 'Success', $user);
    }

    /**
     * query get a user by email
     *
     * @param  string $email
     * @return User|null
     */
    private function getUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * wrap a result into response json
     *
     * @param  int $code
     * @param  string $message
     * @param  array $resource
     * @return \Illuminate\Http\JsonResponse
     */
    private function wrapResponse(int $code, string $message, array $resource): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $resource['data'],
            'token' => $resource['token']
        ]);
    }
}
