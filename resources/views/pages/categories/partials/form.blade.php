<div class="form-group">
    <label for="name">Category name</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name"
        value="{{ isset($category) ? old('name', $category->name) : old('name') }}">
    @error('name')
    <small class="fw-bold invalid-feedback">{{ $message }}</small>
    @enderror
</div>

@empty($category)
    <div class="form-group">
        <label for="image">Category image</label>
        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" id="image">
        @error('image')
        <small class="fw-bold invalid-feedback">{{ $message }}</small>
        @enderror
    </div>
@endempty

@isset($category)
    <div class="form-group">
        <div class="row">
            <div class="col-4">
                <p>Current image</p>
                <img src="{{ $category->getCategoryImage() }}" alt="Category image"
                    class="img-thumbnail img-fluid rounded-circle w-50">
            </div>
            <div class="col-8 my-auto">
                <label for="image">Category image</label>
                <input id="image" name="image" type="file" class="form-control @error('image') is-invalid @enderror">
                @error('image')
                <small class="fw-bold invalid-feedback">{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>
@endisset
