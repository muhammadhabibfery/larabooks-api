<div class="col-md-12 {{ $type === 'trash' ? 'mt-4' : '' }}">
    <div class="row">
        <div class="col-md-6">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link {{ request()->role === null ? 'active' : '' }}"
                        href="{{ route($routeName, ['keyword' => request()->keyword, 'status' => request()->status]) }}">
                        All
                    </a>
                </li>
                @foreach ($roles as $role)
                <li class="nav-item">
                    <a class="nav-link {{ request()->role === $role ? 'active' : '' }}"
                        href="{{ route($routeName, ['role' => $role, 'keyword' => request()->keyword, 'status' => request()->status]) }}">
                        {{ $role }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        @if ($type === 'trash')
        <div class="col-md-6 text-right">
            <a href="{{ route('users.index') }}" class="btn btn-outline-blue w-25">Back</a>
        </div>
        @endif
    </div>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">No.</th>
                <th scope="col">Name</th>
                <th scope="col">Username</th>
                <th scope="col">Email</th>
                <th scope="col">Role</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
            <tr>
                <th scope="row">{{ $users->currentPage() * 10 - 10 + $loop->iteration }}</th>
                <td>{{ $user->name }}</td>
                <td>{{ $user->username }}</td>
                <td>{{ $user->email }}</td>
                <td class="font-weight-bold">{{ $user->string_role }}</td>
                <td>
                    <span class="{{ $user->status == 'ACTIVE' ? 'text-blue' : 'text-danger' }} text-monospace">
                        {{ $user->status }}
                    </span>
                </td>
                <td>
                    @if ($type === 'index')
                    <a href="{{ route('users.show', $user) }}" class="btn btn-success btn-sm my-1">Detail</a>
                    @can('update', $user)
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm my-1">Edit</a>
                    @endcan
                    @else
                    <a href="{{ route('users.trash.show', $user) }}" class="btn btn-success btn-sm my-1">Detail</a>
                    <a href="{{ route('users.trash.restore', $user) }}" class="btn btn-warning btn-sm my-1"
                        onclick="return confirm('Are you sure want to restore customer {{ $user->name }} ?')">Restore</a>
                    @endif
                    @can('delete', $user)
                    <a href="#" class="btn btn-danger btn-sm" data-toggle="modal"
                        data-target="#deleteUser{{ $user->username }}Modal">Delete</a>
                    @endcan
                </td>

                {{-- Delete User Modal --}}
                <div class="modal fade" id="deleteUser{{ $user->username }}Modal" tabindex="-1" role="dialog"
                    aria-labelledby="deleteUserModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Delete User</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>
                                    Are you sure want to {{ $type === 'trash' ? ' permanently' : '' }} delete user {{
                                    $user->name }} ?
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <form
                                    action="{{ $type === 'index' ? route('users.destroy', $user) : route('users.trash.force-delete', $user) }}"
                                    method="POST" id="myfr">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-primary" id="btnfr">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <p class="font-weight-bold text-center text-monospace">
                        {{ $type === 'trash' ? 'Deleted users' : 'users' }} not available
                    </p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $users->withQueryString()->onEachSide(2)->links() }}
    </div>
</div>
