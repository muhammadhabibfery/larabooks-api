<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Traits\ImageHandler;
use App\User;

class ProfileController extends Controller
{
    use ImageHandler;

    /**
     * The name of user property
     *
     * @var mixed
     */
    private $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
    }

    public function editProfile()
    {
        return view('pages.profiles.edit-profile', ['user' => $this->user]);
    }

    /**
     * Update the user's profile.
     *
     * @param  \App\Http\Requests\UserRequest  $request
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
