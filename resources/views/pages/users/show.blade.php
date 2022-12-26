@extends('layouts.global')

@section('title', 'Detail User')

@section('pageTitle', 'Detail User')

@section('content')
<x-users.user-detail :user="$user" :route="route('users.index')" />
@endsection
