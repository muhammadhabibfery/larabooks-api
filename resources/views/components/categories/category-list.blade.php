<div class="col-12 {{ $type === 'trash' ? 'mt-4' : '' }}">
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">No.</th>
                <th scope="col">Name</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $category)
            <tr>
                <th scope="row">{{ $categories->currentPage() * 10 - 10 + $loop->iteration }}</th>
                <td>{{ $category->name }}</td>
                <td>
                    @if ($type === 'index')
                    <a href="{{ route('categories.show', $category) }}" class="btn btn-success btn-sm my-1">Detail</a>
                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning btn-sm my-1">Edit</a>
                    @else
                    <a href="{{ route('categories.trash.show', $category) }}"
                        class="btn btn-success btn-sm my-1">Detail</a>
                    <a href="{{ route('categories.trash.restore', $category) }}" class="btn btn-warning btn-sm my-1"
                        onclick="return confirm('Are you sure want to restore category {{ $category->name }} ?')">
                        Restore
                    </a>
                    @endif
                    @can('delete', $category)
                    <a href="#" class="btn btn-danger btn-sm" data-toggle="modal"
                        data-target="#deleteCategory{{ $category->slug }}Modal">Delete</a>
                    @endcan
                </td>

                {{-- Delete Category Modal --}}
                <div class="modal fade" id="deleteCategory{{ $category->slug }}Modal" tabindex="-1" role="dialog"
                    aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Delete Category</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>
                                    Are you sure want to {{ $type === 'trash' ? ' permanently' : '' }} delete category
                                    {{ $category->name }} ?
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <form
                                    action="{{ $type === 'index' ? route('categories.destroy', $category->slug) : route('categories.trash.force-delete', $category) }}"
                                    method="POST" id="myfr">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-primary" id="btnfr">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </tr>
            @empty
            <tr>
                <td colspan="3">
                    <p class="font-weight-bold text-center text-monospace">
                        {{ $type === 'trash' ? 'Deleted categories' : 'Categories' }} not available
                    </p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $categories->withQueryString()->onEachSide(2)->links() }}
    </div>
</div>
