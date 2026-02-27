@extends('admin.layouts.app')

@section('title', 'Edit Safari Category')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Category: {{ $safariCategory->name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.safari-categories.index') }}">Safari Categories</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card card-info card-outline shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">Update Information</h3>
                    </div>
                    
                    <form action="{{ route('admin.safari-categories.update', $safariCategory->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="name">Category Name</label>
                                <input type="text" name="name" id="name" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $safariCategory->name) }}" required>
                                @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Slug (URL Identifier)</label>
                                <input type="text" class="form-control bg-light" value="{{ $safariCategory->slug }}" readonly>
                                <small class="text-muted">Slug is automatically updated based on the name.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" rows="5" 
                                          class="form-control @error('description') is-invalid @enderror">{{ old('description', $safariCategory->description) }}</textarea>
                                @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="card-footer bg-white border-top-0">
                            <button type="submit" class="btn btn-info text-white px-4">
                                <i class="fas fa-sync-alt mr-1"></i> Update Category
                            </button>
                            <a href="{{ route('admin.safari-categories.index') }}" class="btn btn-link text-muted">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection