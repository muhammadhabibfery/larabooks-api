<div class="col-md-12">
    <form action="{{ $route }}" method="get">
        <div class="input-group mb-3">
            <input type="text" name="keyword" class="form-control w-25"
                placeholder="Search by customer name or book title" value="{{ request()->keyword }}">
            <div class=" mx-4 align-self-center">
                <select name="status" id="status" class="form-control orders" style="width: 10em">
                    <option value=""></option>
                    @foreach ($status as $s)
                    <option value="{{ $s }}" {{ request()->status == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-primary mr-1" type="submit" id="button-addon2">Search</button>
            <a href="{{ $route }}" class="btn btn-dark mx-2" id="button-addon2">Reset</a>

            @if ($type === 'trash')
            <a href="{{ route('orders.index') }}" class="btn btn-outline-blue">Back</a>
            @endif
        </div>
</div>
</form>
</div>
