<?php

use Livewire\Component; // Using Volt syntax based on your setup
use App\Models\User;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

new class extends Component
{
    use WithPagination;

    public $search = '';
    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

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

    /**
     * Eager loading roles and permissions here is crucial for performance.
     */
    #[Computed]
    public function users()
    {
        return User::with(['roles', 'permissions']) // Eager load Spatie relations
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
            <div class="col-md-6">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-users mr-2 text-pink"></i> System Users
                </h3>
            </div>
            <div class="col-md-6 text-right">
                <div class="input-group">
                    <input type="text" 
                           wire:model.live.debounce.300ms="search" 
                           class="form-control" 
                           placeholder="Search by name, email, or role...">
                    <div class="input-group-append">
                        <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        @if (session()->has('success'))
            <div class="alert alert-success m-3 alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif
        
        @if (session()->has('error'))
            <div class="alert alert-danger m-3 alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 30%">User Details</th>
                        <th>Email</th>
                        <th>Roles & Privileges</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->users as $user)
                        <tr wire:key="user-{{ $user->id }}">
                            <td>
                                <div class="d-flex align-items-center">
                                    {{-- Use stored photo if exists, else fallback to UI Avatars --}}
                                    <img src="{{ $user->photo ? asset($user->photo) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=f8bbd0&color=880e4f' }}" 
                                         class="img-circle mr-3 border shadow-sm" width="40" height="40" style="object-fit: cover;">
                                    <div>
                                        <div class="font-weight-bold">{{ $user->name }}</div>
                                        <small class="text-muted">Joined {{ $user->created_at->format('M Y') }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">{{ $user->email }}</td>
                            <td class="align-middle">
                                {{-- Show All Roles --}}
                                @foreach($user->roles as $role)
                                    <span class="badge badge-pink px-2 py-1 mb-1">{{ ucfirst($role->name) }}</span>
                                @endforeach

                                {{-- Show Direct Permissions --}}
                                @if($user->permissions->count() > 0)
                                    <div class="mt-1">
                                        @foreach($user->permissions as $permission)
                                            <span class="badge badge-light border text-xs text-muted" title="Direct Permission">
                                                <i class="fas fa-unlock-alt fa-xs"></i> {{ $permission->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                                
                                @if($user->roles->isEmpty() && $user->permissions->isEmpty())
                                    <span class="text-muted small italic">No access assigned</span>
                                @endif
                            </td>
                            <td class="text-right align-middle px-3">
                                <div class="btn-group">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" 
                                       class="btn btn-sm btn-outline-info mr-1" title="Edit User">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <button wire:click="deleteUser({{ $user->id }})" 
                                            wire:confirm="Are you sure you want to delete {{ $user->name }}? This action cannot be undone."
                                            class="btn btn-sm btn-outline-danger" title="Delete User">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="fas fa-search fa-3x text-light mb-3"></i>
                                <p class="text-muted">No users found matching your search.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
        <div class="small text-muted">
            Showing {{ $this->users->firstItem() }} to {{ $this->users->lastItem() }} of {{ $this->users->total() }} users
        </div>
        <div>
            {{ $this->users->links() }}
        </div>
    </div>
</div>