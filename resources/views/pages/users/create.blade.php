@extends('layouts.global')

@section('title', 'Create User')

@section('pageTitle', 'Tambah User')

@section('content')

<div class="col-md-10">
    <div class="row">
        <div class="col-12">
            <a href="{{ route('users.index') }}" class="btn btn-link btn-outline-primary float-right">Back</a>
        </div>
    </div>

    <x-users.user-form :route="route('users.store')" :roles="$roles" :cities="$cities" :user="null" />
</div>
@endsection

@push('my_styles')
@include('pages.includes._select2-style')
@endpush

@push('my_scripts')
@include('pages.users.includes._select2-users-script')
@endpush
