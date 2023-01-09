<?php

namespace App\Http\Controllers;

use App\City;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * constant of property names
     *
     * @var string
     */
    private const ROUTE_INDEX = 'users.index', ROUTE_TRASH = 'users.trash', ROLES = ['ADMIN', 'STAFF', 'CUSTOMER'];

    /**
     * authorizing the user controller
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = self::ROLES;
        $users = $this->getAllUsersBySearch($request->keyword, $request->status, $request->role, 10);

        return view('pages.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = self::ROLES;
        $cities = $this->getAllCities();
        return view('pages.users.create', compact('roles', 'cities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @return mixed
     */
    public function store(UserRequest $request)
    {
        $data = $this->mergeData($request);
        $successMessage = 'User successfully created';

        return $this->checkProcess(self::ROUTE_INDEX, $successMessage, function () use ($data) {
            if (!User::create($data)) throw new \Exception('Failed to create user');
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('pages.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = self::ROLES;
        return view('pages.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $user
     * @return mixed
     */
    public function update(UserRequest $request, User $user)
    {
        $data = $this->mergeData($request, false);
        $successMessage = 'User successfully updated';

        return $this->checkProcess(self::ROUTE_INDEX, $successMessage, function () use ($user, $data) {
            if (!$user->update($data)) throw new \Exception('Failed to update user');
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function destroy(User $user)
    {
        $successMessage = 'User successfully deleted';
        $failedMessage = 'Failed to delete user';

        return $this->checkProcess(self::ROUTE_INDEX, $successMessage, function () use ($user, $failedMessage) {
            if (!$user->update(['deleted_by' => auth()->id()])) throw new \Exception($failedMessage);
            if (!$user->delete()) throw new \Exception($failedMessage);
        }, true);
    }

    /**
     * Display a listing of the deleted resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function indexTrash(Request $request)
    {
        $roles = self::ROLES;
        $users = $this->getAllUsersBySearch($request->keyword, $request->status, $request->role, 10, true);

        return view('pages.users.trash.index-trash', compact('roles', 'users'));
    }

    /**
     * Display the specified deleted resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function showTrash(User $user)
    {
        return view('pages.users.trash.show-trash', compact('user'));
    }

    /**
     * restore the specified deleted resource.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function restore(User $user)
    {
        $successMessage = 'User successfully restored';
        $failedMessage = "Failed to restore user";

        return $this->checkProcess(self::ROUTE_TRASH, $successMessage, function () use ($user, $failedMessage) {
            if (!$user->update(['deleted_by' => null])) throw new \Exception($failedMessage);
            if (!$user->restore()) throw new \Exception($failedMessage);
        }, true);
    }

    /**
     * Remove the specified deleted resource from storage.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function forceDelete(User $user)
    {
        $successMessage = 'User successfully deleted permanently';
        return $this->checkProcess(self::ROUTE_TRASH, $successMessage, function () use ($user) {
            if (!$user->forceDelete()) throw new \Exception('Failed to delete user');
        });
    }

    /**
     * query all users by search
     *
     * @param  string $keyword
     * @param  string $status
     * @param  string $role
     * @param  int $number (define paginate data per page)
     * @param  bool $onlyDeleted (only trashed when delete using soft delete)
     * @return \illuminate\Pagination\LengthAwarePaginator
     */
    private function getAllUsersBySearch(?string $keyword, ?string $status, ?string $role, int $number, $onlyDeleted = false)
    {
        $users = User::where('name', 'LIKE', "%$keyword%");

        if ($status && in_array($status, ['ACTIVE', 'INACTIVE'])) $users->where('status', $status);

        if ($role && in_array($role, self::ROLES)) $users->whereJsonContains('role', $role);

        if ($onlyDeleted) $users->onlyTrashed();

        return $users->latest()
            ->paginate($number);
    }

    /**
     * query all cities
     *
     * @return \App\City
     */
    private function getAllCities()
    {
        return City::latest()->get();
    }

    /**
     * merge data into an array
     *
     * @param  object $request
     * @param  bool $store (merge data when store a new user or update existing user)
     * @return array
     */
    private function mergeData(object $request, ?bool $store = true)
    {
        $validatedData = $request->validated();

        if ($store) {
            $additionalData = [
                'username' => strtolower('staff' . '_' . head(explode(' ', $validatedData['name'])) . rand(0, 99)),
                'role' => 'STAFF',
                'status' => 'ACTIVE',
                'password' => Hash::make($validatedData['nik']),
                'created_by' => auth()->id()
            ];
        } else {
            $additionalData = [
                'updated_by' => auth()->id()
            ];
        }

        return array_merge($validatedData, $additionalData);
    }

    /**
     * Check one or more processes and catch them if fail
     *
     * @param  string $routeName
     * @param  string $successMessage
     * @param  callable $action
     * @param  bool $dbTransaction (use database transaction for multiple queries)
     * @return \Illuminate\Http\Response
     */
    private function checkProcess(string $routeName, string $successMessage, callable $action, ?bool $dbTransaction = false)
    {
        try {
            if ($dbTransaction) DB::beginTransaction();

            $action();

            if ($dbTransaction) DB::commit();
        } catch (\Exception $e) {
            if ($dbTransaction) DB::rollback();

            return redirect()->route($routeName)
                ->with('failed', $e->getMessage());
        }

        return redirect()->route($routeName)
            ->with('success', $successMessage);
    }
}
