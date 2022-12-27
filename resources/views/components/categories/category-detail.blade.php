<div class="card mb-3">
    <div class="row no-gutters">
        <div class="col-md-4 align-self-center">
            <img src="{{ $category->getImage() }}" class="card-img" alt="Category image">
        </div>
        <div class="col-md-8">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <h5 class="card-title">Category name</h5>
                        <p class="card-text">{{ $category->name }}</p>
                        <h5 class="card-title">Related Book(s)</h5>
                        <ul>
                            @forelse ($category->books as $book)
                            <li>{{ $book->title }}</li>
                            @empty
                            <p class="font-weight-bold">-</p>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <div class="mt-3">
                    @if ($category->created_by)
                    <p class="card-text d-inline-block mr-2">
                        <small class="text-muted">
                            Created by {{ createdUpdatedDeletedBy($category->created_by) }}
                        </small>
                    </p>
                    @endif
                    @if ($category->updated_by)
                    <p class="card-text d-inline-block mr-2">
                        <small class="text-muted">
                            Last updated by {{ createdUpdatedDeletedBy($category->updated_by) }}
                            ({{ $category->updated_at->diffForHumans() }})
                        </small>
                    </p>
                    @endif
                    @if ($category->deleted_by)
                    <p class="card-text d-inline-block mr-2">
                        <small class="text-muted">
                            Deleted by {{ createdUpdatedDeletedBy($category->deleted_by) }}
                        </small>
                    </p>
                    @endif
                    <a href="{{ $route }}" class="btn btn-link btn-outline-primary float-right">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
