<div class="col-md-12">
    <div class="card mb-3">
        <div class="row no-gutters">
            <div class="col-md-4 align-self-center">
                <img src="{{ $user->getAvatar() }}" class="card-img" alt="avatar user">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h5 class="card-title">Name</h5>
                            <p class="card-text">{{ $user->name }}</p>
                            <h5 class="card-title">Username</h5>
                            <p class="card-text">{{ $user->username }}</p>
                            <h5 class="card-title">roles</h5>
                            <ul>
                                @foreach ($user->role as $role)
                                <li>{{ $role }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-6">
                            <h5 class="card-title">Email</h5>
                            <p class="card-text">{{ $user->email }}</p>
                            <h5 class="card-title">Phone Number</h5>
                            <p class="card-text">{{ $user->phone }}</p>
                            <h5 class="card-title">Address</h5>
                            <p class="card-text">{{ $user->address }}</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        @if ($user->created_by)
                        <p class="card-text d-inline-block mr-2">
                            <small class="text-muted">
                                Created by {{ createdUpdatedDeletedBy($user->created_by) }}
                            </small>
                        </p>
                        @endif
                        @if ($user->updated_by)
                        <p class="card-text d-inline-block mr-2">
                            <small class="text-muted">
                                Last updated by {{ createdUpdatedDeletedBy($user->updated_by) }}
                                ({{ $user->updated_at->diffForHumans() }})
                            </small>
                        </p>
                        @endif
                        @if ($user->deleted_by)
                        <p class="card-text d-inline-block mr-2">
                            <small class="text-muted">
                                Deleted by {{ createdUpdatedDeletedBy($user->deleted_by) }}
                            </small>
                        </p>
                        @endif
                        <a href="{{ $route }}" class="btn btn-link btn-outline-primary float-right">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
