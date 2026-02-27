@extends('admin.layouts.app')

@section('title', 'Create Safari Package')

@section('content')
    {{-- Call the Volt Component --}}
    <livewire:admin.safari.create-package />
@endsection