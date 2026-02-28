<?php

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Spatie\Permission\Models\Role;

new class extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = ''; // New property for filtering by role
    protected $paginationTheme = 'bootstrap';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingRoleFilter() { $this->resetPage(); }

    public function deleteUser($id)
    {
        if ($id === auth()->id()) {
            session()->flash('error', 'You cannot delete yourself!');
            return;
        }

        $user = User::find($id);
        if ($user) {
            $user->delete();
            session()->flash('success', 'User deleted successfully.');
        }
    }

    #[Computed]
    public function roles()
    {
        return Role::all();
    }

    #[Computed]
    public function users()
    {
        return User::with(['roles', 'permissions'])
            ->when($this->roleFilter, function($query) {
                $query->role($this->roleFilter);
            })
            ->where(function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);
    }
};
?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <div class="row align-items-center">
            <div class="col-md-4">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-users mr-2 text-pink"></i> System Users
                </h3>
            </div>
            <div class="col-md-8 d-flex justify-content-end gap-2">
                <select wire:model.live="roleFilter" class="form-control w-25 mr-2">
                    <option value="">All Roles</option>
                    @foreach($this->roles as $role)
                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>

                <div class="input-group w-50">
                    <input type="text" wire:model.live.debounce.300ms="search" 
                           class="form-control" placeholder="Search name or email...">
                    <div class="input-group-append">
                        <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        @include('admin.partials.alerts') {{-- Optional: move alerts to partial --}}

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light text-uppercase small font-weight-bold">
                    <tr>
                        <th>User</th>
                        <th>Login Method</th>
                        <th>Status</th>
                        <th>Roles & Permissions</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->users as $user)
                        <tr wire:key="user-{{ $user->id }}">
                            <td>
                                <div class="d-flex align-items-center">
                                    {{-- Image Priority: 1. Uploaded Photo | 2. Google Avatar | 3. UI-Avatar --}}
                                    @php
                                        $photoUrl = $user->photo ? asset($user->photo) : 
                                                   ($user->avatar ? $user->avatar : 
                                                   'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=f8bbd0&color=880e4f');
                                    @endphp
                                    <img src="{{ $photoUrl }}" class="img-circle mr-3 shadow-sm border" width="40" height="40" style="object-fit: cover;">
                                    <div>
                                        <div class="font-weight-bold">{{ $user->name }}</div>
                                        <div class="small text-muted text-truncate" style="max-width: 150px;">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">
                                @if($user->provider === 'google')
                                    <span class="badge badge-light border text-dark">
                                        <i class="fab fa-google text-danger mr-1"></i> Google
                                    </span>
                                @else
                                    <span class="badge badge-light border text-dark">
                                        <i class="fas fa-envelope text-primary mr-1"></i> Email/Pass
                                    </span>
                                @endif
                            </td>
                            <td class="align-middle">
                                @if($user->status)
                                    <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Active</span>
                                @else
                                    <span class="badge badge-secondary"><i class="fas fa-times-circle mr-1"></i> Inactive</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                @foreach($user->roles as $role)
                                    <span class="badge badge-pink px-2 py-1 mb-1">{{ ucfirst($role->name) }}</span>
                                @endforeach
                                @if($user->permissions->count() > 0)
                                    <div class="text-xs text-muted"><i class="fas fa-key fa-xs"></i> {{ $user->permissions->count() }} custom perms</div>
                                @endif
                            </td>
                            <td class="text-right align-middle px-3">
                                <div class="btn-group">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-info mr-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button wire:click="deleteUser({{ $user->id }})" 
                                            wire:confirm="Delete {{ $user->name }}?" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-5 text-muted">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
        <div class="small text-muted text-uppercase font-weight-bold">
            Total Users: {{ $this->users->total() }}
        </div>
        <div>{{ $this->users->links() }}</div>
    </div>
</div>