@extends('admin.layouts.app')

@section('title', 'Edit User: ' . $user->name)

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1 class="font-weight-bold text-dark">Edit User Profile</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header bg-white">
                <h3 class="card-title">Account Details for <strong>{{ $user->email }}</strong></h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror shadow-sm" required>
                            @error('name')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror shadow-sm" required>
                            @error('email')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">New Password <small class="text-muted">(Optional)</small></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror shadow-sm" placeholder="Leave blank to keep current">
                            @error('password')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control shadow-sm">
                        </div>

                        <hr class="col-12 my-4">

                        @if(auth()->user()->hasRole('super-admin'))
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-primary"><i class="fas fa-user-shield mr-1"></i> Assign Roles</label>
                                <select name="roles[]" class="form-control select2 shadow-sm" multiple data-placeholder="Select Roles">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Users can have multiple roles (e.g., Safari Manager + Guide)</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-info"><i class="fas fa-key mr-1"></i> Direct Permissions</label>
                                <select name="permissions[]" class="form-control select2 shadow-sm" multiple data-placeholder="Select Extra Permissions">
                                    @foreach($permissions as $permission)
                                        <option value="{{ $permission->name }}" {{ $user->hasPermissionTo($permission->name) ? 'selected' : '' }}>
                                            {{ ucfirst($permission->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Override role-based permissions if necessary</small>
                            </div>
                        @endif

                        <div class="col-md-6 mb-3 mt-3">
                            <label class="form-label font-weight-bold">Profile Picture</label>
                            <div x-data="{ photoPreview: null }">
                                <div class="custom-file">
                                    <input type="file" name="photo" class="custom-file-input" id="customFile" accept="image/*"
                                        @change="const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL($event.target.files[0])">
                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                </div>

                                <div class="mt-3 d-flex align-items-center">
                                    <div class="mr-3">
                                        <p class="small text-muted mb-1">Current/Preview:</p>
                                        <template x-if="photoPreview">
                                            <img :src="photoPreview" class="img-circle elevation-2" style="width: 80px; height: 80px; object-fit: cover;">
                                        </template>
                                        <template x-if="!photoPreview">
                                            <img src="{{ $user->photo ? asset($user->photo) : ($user->avatar ?? asset('dist/img/avatar5.png')) }}" 
                                                 class="img-circle elevation-2" style="width: 80px; height: 80px; object-fit: cover;">
                                        </template>
                                    </div>
                                    <div class="small text-muted italic">
                                        Supports JPG, PNG. Max 2MB.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3 mt-3">
                            <label class="form-label font-weight-bold">Account Status</label>
                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success mt-2">
                                <input type="checkbox" name="status" class="custom-control-input" id="statusSwitch" value="1" {{ $user->status ? 'checked' : '' }}>
                                <label class="custom-control-label" for="statusSwitch">Toggle User Access (Active/Inactive)</label>
                            </div>
                            <small class="text-muted">Inactive users cannot log into the system.</small>
                        </div>
                    </div>

                    <div class="card-footer bg-white px-0 pt-4">
                        <button type="submit" class="btn btn-primary px-4 shadow-sm font-weight-bold">
                            <i class="fas fa-user-check mr-1"></i> Update User Profile
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-default px-4 ml-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection