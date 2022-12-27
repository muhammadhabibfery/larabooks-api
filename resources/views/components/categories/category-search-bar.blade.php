<div class="col-md-8">
    <form action="{{ $route }}" method="get">
        <div class="input-group mb-3">
            <input type="text" name="keyword" class="form-control" placeholder="Search by name"
                aria-label="Recipient's username" aria-describedby="button-addon2" value="{{ request()->keyword }}">
            <div class="input-group-append">
                <button class="btn btn-primary mr-4" type="submit" id="button-addon2">Search</button>
            </div>
            <a href="{{ $route }}" class="btn btn-dark btn-lg" id="button-addon2">Reset</a>
        </div>
    </form>
</div>
@if ($type === 'trash')
<div class="col-md-4 text-right">
    <a href="{{ route('categories.index') }}" class="btn btn-outline-blue btn-lg mr-2">Back</a>
</div>
@endif
