<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;

class UserDelete extends Component
{
    #[Locked]
    public $id;

    public $modalUserDelete = false;


    #[Locked]
    public $name;

    #[On('dispatch-admin-crud-user-table-delete')]
    public function set_user($id,$name){

        $this->id = $id;
        $this->name = $name;

        $this->modalUserDelete = true;
    }

    public function del() {
        $del = User::destroy( $this->id );

        ($del)
        ? $this->dispatch('notify', title: 'success',message: 'data berhasil dihapus    ')
        : $this->dispatch('notify', title: 'fail', message: 'data gagal dihapus ');
        // $this->form->reset();
        $this->dispatch('dispatch-admin-crud-user-delete-del')->to(UserTable::class);
        $this->modalUserDelete = false;
    }
    
    public function render()
    {
        return view('livewire..admin.user-delete');
    }
}
