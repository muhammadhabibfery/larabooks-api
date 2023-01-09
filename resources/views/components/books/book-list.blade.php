<div class="col-md-12 {{ $type === 'trash' ? 'mt-4' : '' }}">
    <div class="row">
        <div class="col-md-6">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link {{ request()->status === null ? 'active' : '' }}"
                        href="{{ route($routeName, ['keyword' => request()->keyword]) }}">
                        All
                    </a>
                </li>
                @foreach ($status as $s)
                <li class="nav-item">
                    <a class="nav-link {{ request()->status === $s ? 'active' : '' }}"
                        href="{{ route($routeName, ['status' => $s, 'keyword' => request()->keyword]) }}">
                        {{ $s }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">No.</th>
                <th scope="col">Title</th>
                <th scope="col">Stock</th>
                <th scope="col">Price</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($books as $book)
            <tr>
                <th scope="row">{{ $books->currentPage() * 10 - 10 + $loop->iteration }}</th>
                <td>{{ $book->title }}</td>
                <td>{{ $book->stock }}</td>
                <td>{{ currencyFormat($book->price) }}</td>
                <td>
                    <span class="{{ $book->status == 'PUBLISH' ? 'text-blue' : 'text-danger' }} text-monospace">
                        {{ $book->status }}
                    </span>
                </td>
                <td>
                    @if ($type === 'index')
                    <a href="{{ route('books.show', $book) }}" class="btn btn-success btn-sm my-1">Detail</a>
                    <a href="{{ route('books.edit', $book) }}" class="btn btn-warning btn-sm my-1">Edit</a>
                    @else
                    <a href="{{ route('books.trash.show', $book) }}" class="btn btn-success btn-sm my-1">Detail</a>
                    <a href="{{ route('books.trash.restore', $book) }}" class="btn btn-warning btn-sm my-1"
                        onclick="return confirm('Are you sure want to restore book {{ $book->title }} ?')">Restore</a>
                    @endif
                    <a href=" #" class="btn btn-danger btn-sm" data-toggle="modal"
                        data-target="#deleteBook{{ $book->slug }}Modal">Delete</a>
                </td>

                {{-- Delete Book Modal --}}
                <div class="modal fade" id="deleteBook{{ $book->slug }}Modal" tabindex="-1" role="dialog"
                    aria-labelledby="deleteBook" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Delete Book</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>
                                    Are you sure want to delete book {{ $book->title }} {{ $type === 'trash' ? '
                                    permanently' : '' }} ?
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <form
                                    action="{{ $type === 'index' ? route('books.destroy', $book) : route('books.trash.force-delete', $book) }}"
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
                        {{ $type === 'trash' ? 'Deleted books' : 'books' }} not available
                    </p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $books->withQueryString()->onEachSide(2)->links() }}
    </div>
</div>
