@extends('layouts.app')

@section('content')
    <dashboard :user-level="{{ Auth::user()->level() }}"></dashboard>
@endsection

@push('js')

@endpush