<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use App\Livewire\Forms\AdminUserForm;

class UserCreate extends Component
{
    public AdminUserForm $form;

    public $roles = [];

    public function mount()
    {
        $this->roles = Role::pluck('name')->toArray(); 
    }


    public $modalUserCreate = false;

    public function save() {
        
        $this->validate();

        $simpan = $this->form->store();

    
        $simpan->assignRole($this->form->role);
        
        is_null($simpan)
        ? $this->dispatch('notify', title: 'fail', message: 'data gagal disimpan')
        : $this->dispatch('notify', title: 'success', message: 'data berhasil disimpan');
        $this->form->reset();
        $this->dispatch('dispatch-admin-crud-user-create-save')->to(UserTable::class);
        $this->modalUserCreate = false;
    }

    public function render()
    {
        return view('livewire..admin.user-create');
    }
}
