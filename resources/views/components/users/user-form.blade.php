<form action="{{ $route }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-sm p-3" id="myfr">
    @csrf

    @if (isset($user))
    @method('PATCH')
    @endif

    <div class="form-group">
        <label for="name">Name</label>
        <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" id="name"
            value="{{ isset($user) ? old('name',$user->name) : old('name') }}" {{ isset($user) ? 'disabled' : '' }}>
        @error('name')
        <small class="fw-bold invalid-feedback">{{ $message }}</small>
        @enderror
    </div>
    @empty($user)
    <div class="form-group">
        <label for="email">Email</label>
        <input class="form-control @error('email') is-invalid @enderror" type="text" name="email" id="email"
            value="{{ isset($user) ? old('email',$user->email) : old('email') }}" {{ isset($user) ? 'disabled' : '' }}>
        @error('email')
        <small class="fw-bold invalid-feedback">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label for="nik">nik</label>
        <input class="form-control @error('nik') is-invalid @enderror" type="text" name="nik" id="nik"
            value="{{ isset($user) ? old('nik',$user->nik) : old('nik') }}" {{ isset($user) ? 'disabled' : '' }}>
        @error('nik')
        <small class="fw-bold invalid-feedback">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label for="phone">Phone number</label>
        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
            value="{{ isset($user) ? old('phone',$user->phone) : old('phone') }}" {{ isset($user) ? 'disabled' : '' }}>
        @error('phone')
        <small class="fw-bold invalid-feedback">{{ $message }}</small>
        @enderror
    </div>
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
        <label for="address">Address</label>
        <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" {{
            isset($user) ? 'disabled' : ''
            }}>{{ isset($user) ? old('address',$user->address) : old('address') }} </textarea>
        @error('address')
        <small class="fw-bold invalid-feedback">{{ $message }}</small>
        @enderror
    </div>
    @endempty
    @isset($user)
    <div class="form-group">
        <label class="d-block">Role</label>
        @foreach ($roles as $role)
        @if ($loop->last)
        @break
        @endif
        <input class="form-control" type="radio" name="role" id="{{ $role }}" value="{{ $role }}" {{ (old('role',
            $user->string_role) === $role ? 'checked' : '') }}>
        <label for="{{ $role }}">{{ $role }}</label>
        @endforeach
        @error('role')
        <small class="fw-bold invalid-feedback d-block">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label class="d-block">Status</label>
        <input type="radio" name="status" id="active" class="form-control @error('status') is-invalid @enderror"
            value="ACTIVE" {{ (old('status', $user->status) === 'ACTIVE' ? 'checked' : '') }}>
        <label for="active">Active</label>
        <input type="radio" name="status" id="inactive" class="form-control @error('status') is-invalid @enderror"
            value="INACTIVE" {{ (old('status', $user->status) === 'INACTIVE' ? 'checked' : '') }}>
        <label for="inactive">Inactive</label>
        @error('status')
        <small class="fw-bold d-block invalid-feedback">{{ $message }}</small>
        @enderror
    </div>
    @endisset
    <button type="submit" class="btn btn-primary btn-block" id="btnfr">{{ isset($user) ? 'Edit' : 'Create' }}</button>
</form>
