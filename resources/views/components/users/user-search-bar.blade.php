<div class="col-md-8">
    <form action="{{ $route }}" method="get">
        <div class="input-group mb-3">
            <input type="hidden" name="role" value="{{ request()->role ?: null }}">
            <input type="text" name="keyword" class="form-control w-25" placeholder="Search by name"
                value="{{ request()->keyword }}">
            <div class="ml-3 align-self-center">
                <input type="radio" name="status" class="form-control" id="active" value="ACTIVE" {{ request()->status
                == 'ACTIVE' ? 'checked' : '' }}>
                <label for="active">ACTIVE</label>
            </div>
            <div class="px-1 align-self-center">
                <input type="radio" name="status" class="form-control" id="inactive" value="INACTIVE" {{
                    request()->status == 'INACTIVE' ? 'checked' : '' }}>
                <label for="inactive">INACTIVE</label>
            </div>
            <button class="btn btn-primary mr-1" type="submit" id="button-addon2 btnfr">Search</button>
            <a href="{{ $route }}" class="btn btn-dark" id="button-addon2">Reset</a>
        </div>
    </form>
</div>
