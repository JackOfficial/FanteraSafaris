@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Safari Categories</h1></div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.safari-categories.create') }}" class="btn btn-primary">Add New</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Packages Count</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td><strong>{{ $category->name }}</strong></td>
                        <td><code>{{ $category->slug }}</code></td>
                        <td>{{ $category->safariPackages()->count() }}</td>
                        <td class="text-right">
                            <a href="{{ route('admin.safari-categories.edit', $category) }}" class="btn btn-sm btn-info"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.safari-categories.destroy', $category) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete category?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $categories->links() }}</div>
    </div>
</section>
@endsection