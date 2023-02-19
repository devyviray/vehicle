@extends('layouts.app')

@section('content')
    <dashboard :user-level="{{ session('level') }}" role="{{ session('role-name') }}" ></dashboard>
@endsection

@push('js')

@endpush