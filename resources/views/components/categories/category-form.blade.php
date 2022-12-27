<form action="{{ $route }}" method="post" enctype="multipart/form-data" class="bg-white shadow-sm p-3" id="myfr">
    @csrf

    @if (isset($category))
    @method('PATCH')
    @endif

    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name"
            value="{{ isset($category) ? old('name', $category->name) : old('name') }}">
        @error('name')
        <small class="fw-bold invalid-feedback">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <div class="row">
            @isset($category)
            <div class="col-4">
                <p>Current image</p>
                <img src="{{ $category->getImage() }}" alt="Category image"
                    class="img-thumbnail img-fluid rounded-circle w-50">
            </div>
            @endisset
            <div class="{{ isset($category) ? 'col-8 my-auto' : 'col-12' }}">
                <label for="image">Category image</label>
                <input id="image" name="image" type="file" class="form-control @error('image') is-invalid @enderror">
                @error('image')
                <small class="fw-bold invalid-feedback">{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary btn-block" id="btnfr">
        {{ isset($category) ? 'Edit' : 'Create' }}
    </button>
</form>
