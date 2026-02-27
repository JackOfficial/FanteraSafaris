<?php

use Livewire\Component;
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
     * Using a Computed property is the modern way to handle 
     * dynamic data in render-less components.
     */
    #[Computed]
    public function users()
    {
        return User::where(function($query) {
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
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       class="form-control d-inline-block w-75" 
                       placeholder="Search users...">
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        @if (session()->has('success'))
            <div class="alert alert-success m-3">{{ session('success') }}</div>
        @endif

        <table class="table table-hover mb-0">
            <thead class="bg-light">
                <tr>
                    <th>User Details</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                {{-- Accessing the computed property via $this->users --}}
                @forelse($this->users as $user)
                    <tr wire:key="user-{{ $user->id }}">
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}" 
                                     class="img-circle mr-2" width="35">
                                <strong>{{ $user->name }}</strong>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge badge-info">{{ $user->getRoleNames()->first() ?? 'User' }}</span>
                        </td>
                       <td class="text-right">
    <div class="btn-group">
        {{-- The missing Edit Link --}}
        <a href="{{ route('admin.users.edit', $user->id) }}" 
           class="btn btn-sm btn-info mr-1">
            <i class="fas fa-edit"></i>
        </a>

        {{-- The Delete Button --}}
        <button wire:click="deleteUser({{ $user->id }})" 
                wire:confirm="Are you sure you want to delete {{ $user->name }}?"
                class="btn btn-sm btn-danger">
            <i class="fas fa-trash"></i>
        </button>
    </div>
</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="card-footer bg-white">
        {{ $this->users->links() }}
    </div>
</div>