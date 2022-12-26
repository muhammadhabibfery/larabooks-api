<div class="form-group">
    <label for="title">Title</label>
    <input class="form-control @error('title') is-invalid @enderror" type="text" name="title" id="title"
        value="{{ isset($book) ? old('title', $book->title) : old('title') }}">
    @error('title')
    <small class="fw-bold text-danger">{{ $message }}</small>
    @enderror
</div>
<div class="form-group">
    <label for="categories">Category</label>
    <select class="categories form-control" name="categories[]" multiple="multiple" id="categories">
        @foreach ($categories as $category)
        <option value="{{ encrypt($category->id) }}" <?php if (old('categories') !=null) {
                collect(old('categories'))->map(function ($item, $key) use ($category) {
                if (decrypt($item) == $category->id) {
                echo 'selected';
                }
                });
                } else {
                if (isset($book)) {
                echo $book->categories->contains('id', $category->id) ? 'selected' : '';
                }
                } ?>>
            {{ $category->name }}
        </option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="description">Description</label>
    <textarea name="description" class="form-control @error('description') is-invalid @enderror"
        id="description">{{ isset($book) ? old('description', $book->description) : old('description') }}</textarea>
    @error('description')
    <small class="fw-bold text-danger">{{ $message }}</small>
    @enderror
</div>
<div class="form-group">
    <label for="stock">Stock</label>
    <input class="form-control @error('stock') is-invalid @enderror" type="number" name="stock" id="stock"
        value="{{ isset($book) ? old('stock', $book->stock) : old('stock') }}">
    @error('stock')
    <small class="fw-bold text-danger">{{ $message }}</small>
    @enderror
</div>
<div class="form-group">
    <label for="author">Author</label>
    <input class="form-control @error('author') is-invalid @enderror" type="text" name="author" id="author"
        value="{{ isset($book) ? old('author', $book->author) : old('author') }}">
    @error('author')
    <small class="fw-bold text-danger">{{ $message }}</small>
    @enderror
</div>
<div class="form-group">
    <label for="publisher">Publisher</label>
    <input class="form-control @error('publisher') is-invalid @enderror" type="text" name="publisher" id="publisher"
        value="{{ isset($book) ? old('publisher', $book->publisher) : old('publisher') }}">
    @error('publisher')
    <small class="fw-bold text-danger">{{ $message }}</small>
    @enderror
</div>
<div class="form-group">
    <label for="price">Price</label>
    <input class="form-control @error('price') is-invalid @enderror" type="number" name="price" id="price"
        value="{{ isset($book) ? old('price', $book->price) : old('price') }}">
    @error('price')
    <small class="fw-bold text-danger">{{ $message }}</small>
    @enderror
</div>
<div class="form-group">
    <label class="d-block">Book status</label>
    <input type="radio" name="action" id="publish" class="form-control @error('action') is-invalid @enderror"
        value="PUBLISH" <?php if (old('action')) { echo old('action')=='PUBLISH' ? 'checked' : '' ; }
        else { if (isset($book)) { echo $book->status == 'PUBLISH' ? 'checked' : '';
    }
    } ?>>
    <label for="publish">Publsih</label>
    <input type="radio" name="action" id="draft" class="form-control @error('action') is-invalid @enderror"
        value="DRAFT" <?php if (old('action')) { echo old('action')=='DRAFT' ? 'checked' : '' ; } else {
        if (isset($book)) { echo $book->status == 'DRAFT' ? 'checked' : '';
    }
    } ?>>
    <label for="draft">Draft</label>
    <br>
    @error('action')
    <small class="fw-bold d-block text-danger">{{ $message }}</small>
    @enderror
</div>

@empty($book)
<div class="form-group">
    <label for="image">Book cover</label>
    <input id="image" name="image" type="file" class="form-control @error('image') is-invalid @enderror"
        value="{{ old('image') }}">
    @error('image')
    <small class="fw-bold text-danger">{{ $message }}</small>
    @enderror
</div>
@endempty

@isset($book)
<div class="form-group">
    <div class="row">
        <div class="col-4">
            <p>Current Book</p>
            <img src="{{ $book->getCoverBook() }}" alt="Cover book" class="img-thumbnail img-fluid rounded-circle w-50">
        </div>
        <div class="col-8 my-auto">
            <label for="image">Book cover</label>
            <input id="image" name="image" type="file" class="form-control @error('image') is-invalid @enderror">
            @error('image')
            <small class="fw-bold text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
</div>
@endisset
