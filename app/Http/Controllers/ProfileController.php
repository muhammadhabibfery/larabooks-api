<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Traits\ImageHandler;
use App\User;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    use ImageHandler;

    /**
     * The name of user property
     *
     * @var mixed
     */
    private $user;

    /**
     * Create instance of authenticated user
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
    }

    /**
     * Show edit profile form
     *
     * @return \Illuminate\Http\Response
     */
    public function editProfile()
    {
        return view('pages.profiles.edit-profile', ['user' => $this->user]);
    }

    /**
     * Update the user's profile.
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $user
     * @return mixed
     */
    public function updateProfile(UserRequest $request, User $user)
    {
        $data = array_merge(
            $request->validated(),
            ['avatar' => $this->setImageFile($request, 'avatars', $user->avatar)]
        );
        $successMessage = 'Your profile has been updated';

        return $this->checkProcess('dashboard', $successMessage, function () use ($user, $data, $request) {
            if ($user->update($data)) {
                $this->createImage($request, $user->avatar, 'avatars');
            } else {
                throw new \Exception('Failed to update your profile');
            }
        });
    }

    /**
     * Show edit password form
     *
     * @return \Illuminate\Http\Response
     */
    public function editPassword()
    {
        return view('pages.profiles.edit-password', ['user' => $this->user]);
    }

    /**
     * Update the user's password.
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $user
     * @return mixed
     */
    public function updatePassword(UserRequest $request, User $user)
    {
        $data = ['password' => Hash::make($request->validated()['new_password'])];
        $successMessage = 'Your password has been updated';

        return $this->checkProcess('dashboard', $successMessage, function () use ($user, $data) {
            if (!$user->update($data)) throw new \Exception('Failed to update your password');
        });
    }

    /**
     * Check one or more processes and catch them if fail
     *
     * @param  string $routeName
     * @param  string $successMessage
     * @param  callable $action
     * @return \Illuminate\Http\Response
     */
    private function checkProcess(string $routeName, string $successMessage, callable $action)
    {
        try {
            $action();
        } catch (\Exception $e) {
            return redirect()->route($routeName)
                ->with('failed', $e->getMessage());
        }

        return redirect()->route($routeName)
            ->with('success', $successMessage);
    }
}
