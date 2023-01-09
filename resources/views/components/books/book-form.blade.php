<form action="{{ $route }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-sm p-3" id="myfr">
    @csrf

    @isset($book)
    @method('PATCH')
    @endisset

    <div class="form-group">
        <label for="city">City</label>
        <select class="city form-control" name="city_id" id="city">
            <option value=""></option>
            @foreach ($cities as $key => $city)
            <option value="{{ $city->id }}" {{ isset($book) ? (old('city_id', $book->city->id) == $city->id ? 'selected'
                : '') : (old('city_id') == $city->id ? 'selected' : '') }}>
                {{ $city->name }}
            </option>
            @endforeach
        </select>
        @error('city_id')
        <small class="font-weight-bold text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label for="title">Title</label>
        <input class="form-control @error('title') is-invalid @enderror" type="text" name="title" id="title"
            value="{{ isset($book) ? old('title', $book->title) : old('title') }}">
        @error('title')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group">
        <label for="categories">Category</label>
        <select class="categories-multiple form-control" name="categories[]" multiple="multiple" id="categories">
            @foreach ($categories as $key => $category)
            <option value="{{ $category->id }}" {{ old('categories') ? (in_array($category->id, old('categories')) ?
                'selected' : '') : ($book ? (in_array($category->id, $book->categories->pluck('id')->toArray()) ?
                'selected' : '')
                :
                '') }}>
                {{ $category->name }}
            </option>
            @endforeach
        </select>
        @error('categories')
        <small class="font-weight-bold text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label for="author">Author</label>
        <input class="form-control @error('author') is-invalid @enderror" type="text" name="author" id="author"
            value="{{ isset($book) ? old('author', $book->author) : old('author') }}">
        @error('author')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group">
        <label for="publisher">Publisher</label>
        <input class="form-control @error('publisher') is-invalid @enderror" type="text" name="publisher" id="publisher"
            value="{{ isset($book) ? old('publisher', $book->publisher) : old('publisher') }}">
        @error('publisher')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
            id="description">{{ isset($book) ? old('description', $book->description) : old('description') }}</textarea>
        @error('description')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group">
        <label for="weight">Berat</label>
        <input class="form-control @error('weight') is-invalid @enderror" type="number" name="weight" id="weight"
            value="{{ isset($book) ? old('weight', $book->weight) : old('weight') }}" placeholder="Gram">
        @error('weight')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group">
        <label for="price">Price</label>
        <input class="form-control @error('price') is-invalid @enderror price" type="number" name="price" id="price"
            value="{{ isset($book) ? old('price', $book->price) : old('price') }}">
        @error('price')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group">
        <label for="stock">Stock</label>
        <input class="form-control @error('stock') is-invalid @enderror" type="number" name="stock" id="stock"
            value="{{ isset($book) ? old('stock', $book->stock) : old('stock') }}">
        @error('stock')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group">
        <label class="d-block">Book status</label>
        @foreach ($status as $s)
        <input type="radio" name="status" id="{{ $s }}" class="form-control @error('status') is-invalid @enderror"
            value="{{ $s }}" {{ $book ? (old('status', $book->status) === $s ? 'checked' : '') : (old('status') === $s ?
        'checked' : '') }}>
        <label for="{{ $s }}">{{ $s }}</label>
        @endforeach
        @error('status')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group">
        <div class="row">
            @isset($book)
            <div class="col-4">
                <p>Current Book</p>
                <img src="{{ $book->getCover() }}" alt="Cover book" class="img-thumbnail img-fluid rounded-circle w-50">
            </div>
            @endisset
            <div class="{{ isset($book) ? 'col-8 my-auto' : 'col-12' }}">
                <label for="image">Book cover</label>
                <input id="image" name="image" type="file" class="form-control @error('image') is-invalid @enderror">
                @error('image')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary btn-block" id="btnfr">
        {{ isset($book) ? 'Edit' : 'Create' }}
    </button>
</form>
