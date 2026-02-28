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
    public $roleFilter = '';
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
            // We count bookings to show activity at a glance
            ->withCount(['bookings as total_bookings']) 
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
                    <i class="fas fa-users-cog mr-2 text-pink"></i> User Management
                </h3>
            </div>
            <div class="col-md-8 d-flex justify-content-end gap-2">
                <select wire:model.live="roleFilter" class="form-control w-25">
                    <option value="">All Roles</option>
                    @foreach($this->roles as $role)
                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>

                <div class="input-group w-50">
                    <input type="text" wire:model.live.debounce.300ms="search" 
                           class="form-control" placeholder="Search name or email...">
                    <div class="input-group-append">
                        <span class="input-group-text bg-white border-left-0"><i class="fas fa-search text-muted"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        @include('admin.partials.alerts')

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light text-uppercase small font-weight-bold text-muted">
                    <tr>
                        <th>User Profile</th>
                        <th>Access Level</th>
                        <th>Activity & Status</th>
                        <th>Safari Stats</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->users as $user)
                        <tr wire:key="user-{{ $user->id }}">
                            <td>
                                <div class="d-flex align-items-center">
                                    @php
                                        $photoUrl = $user->photo ? asset($user->photo) : 
                                                   ($user->avatar ? $user->avatar : 
                                                   'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=f8bbd0&color=880e4f');
                                    @endphp
                                    <img src="{{ $photoUrl }}" class="img-circle mr-3 shadow-sm border" width="45" height="45" style="object-fit: cover;">
                                    <div>
                                        <div class="font-weight-bold text-dark">{{ $user->name }}</div>
                                        <div class="small text-muted">
                                            @if($user->provider === 'google')
                                                <i class="fab fa-google text-danger mr-1"></i> Google Account
                                            @else
                                                <i class="fas fa-envelope-open text-primary mr-1"></i> {{ $user->email }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">
                                @foreach($user->roles as $role)
                                    <span class="badge badge-pink px-2 py-1 mb-1 shadow-sm">{{ ucfirst($role->name) }}</span>
                                @endforeach
                                @if($user->permissions->count() > 0)
                                    <div class="text-xs text-info font-weight-bold"><i class="fas fa-shield-alt"></i> {{ $user->permissions->count() }} Custom Permissions</div>
                                @endif
                            </td>
                            <td class="align-middle">
                                @if($user->status)
                                    <span class="badge badge-success mb-1"><i class="fas fa-check-circle"></i> Active</span>
                                @else
                                    <span class="badge badge-secondary mb-1"><i class="fas fa-ban"></i> Suspended</span>
                                @endif
                                
                                <div class="small text-muted">
                                    <i class="fas fa-history mr-1"></i> 
                                    @if($user->last_login_at)
                                        Seen {{ $user->last_login_at->diffForHumans() }}
                                    @else
                                        Never logged in
                                    @endif
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <span class="h6 mb-0 font-weight-bold">{{ $user->total_bookings }}</span>
                                        <div class="small text-muted text-uppercase" style="font-size: 0.65rem;">Bookings</div>
                                    </div>
                                    @if($user->total_bookings > 0)
                                        <i class="fas fa-map-marked-alt text-pink opacity-50"></i>
                                    @endif
                                </div>
                            </td>
                            <td class="text-right align-middle px-3">
                                <div class="btn-group shadow-sm">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-white text-info border" title="Edit User">
                                        <i class="fas fa-user-edit"></i>
                                    </a>
                                    <button wire:click="deleteUser({{ $user->id }})" 
                                            wire:confirm="Permanent deletion for {{ $user->name }}?" class="btn btn-sm btn-white text-danger border" title="Delete User">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/pink/search-not-found.svg" width="150" class="mb-3">
                                <p class="text-muted">No safari members found matching your criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="card-footer bg-white d-flex justify-content-between align-items-center border-top">
        <div class="text-muted small">
            Displaying <b>{{ $this->users->count() }}</b> of <b>{{ $this->users->total() }}</b> total registered users
        </div>
        <div>{{ $this->users->links() }}</div>
    </div>
</div>