<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use App\Traits\WithSorting;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Livewire\Forms\AdminUserForm;

class UserTable extends Component
{
    use WithPagination, WithSorting;

    public AdminUserForm $form;

    public $paginate = 5; // Jumlah data per halaman
    public $sortBy = 'users.id'; // Kolom default untuk pengurutan
    public $sortDirection = 'desc'; // Arah pengurutan default

    // Realtime proses
    #[On('dispatch-admin-crud-user-create-save')]
    #[On('dispatch-admin-crud-user-update-edit')]
    #[On('dispatch-admin-crud-user-delete-del')]
    
    public function render()
    {
        return view('livewire..admin.user-table', [
            'data' => User::where('id', 'like', '%' . $this->form->id . '%')
                ->where('name', 'like', '%' . $this->form->name . '%')
                ->where('username', 'like', '%' . $this->form->username . '%')
                ->where('email', 'like', '%' . $this->form->email . '%')
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->paginate),
        ]);
    }
}
