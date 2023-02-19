@extends('layouts.app')

@section('content')
    <dashboard :user-level="{{ Auth::user()->level() }}" role="{{ Auth::user()->roles[0]->name }}" ></dashboard>
@endsection

@push('js')

@endpush