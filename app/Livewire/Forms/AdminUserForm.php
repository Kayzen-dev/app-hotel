<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\User;
use Livewire\Attributes\Validate;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserForm extends Form
{

    public ?User $user = null;
    public $id;

    #[Validate('required', message: 'Nama wajib diisi')]
    #[Validate('string', message: 'Nama harus berupa string')]
    #[Validate('min:3', message: 'Nama harus minimal 3 karakter')]
    public $name;

    #[Validate('required', message: 'username wajib diisi')]
    #[Validate('string', message: 'username harus berupa string')]
    #[Validate('min:3', message: 'username harus minimal 3 karakter')]
    public $username;

    #[Validate('required', message: 'Email wajib diisi')]
    #[Validate('email', message: 'Email harus berupa email yang valid')]
    #[Validate('unique:users,email', message: 'Email sudah digunakan, pilih email lain')]
    public $email;

    #[Validate('required', message: 'Password wajib diisi')]
    #[Validate('min:6', message: 'Password minimal 6 karakter')]
    #[Validate('same:confirmPassword', message: 'Password harus cocok dengan konfirmasi')]
    public $password;

    #[Validate('required', message: 'Konfirmasi password wajib diisi')]
    #[Validate('min:6', message: 'Konfirmasi password minimal 6 karakter')]
    public $confirmPassword;


    #[Validate('required', message: 'Role wajib pilih')]
    public $role;
    

    public $roles = [];


    public function mount()
    {
        $this->roles = Role::pluck('name')->toArray();
    }

    public function setUser(User $user)
    {

        $this->user = $user;

        $this->id = $user->id;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->role = $user->roles->pluck('name')->first(); 
    }

    public function store()
    {

        $user = User::create([
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'status_login' => false, 
            'email_verified_at' => now()
        ]);



        $user->assignRole($this->role); 

        return $user;
    }


    public function update($id)
    {
        $this->validate(
            [
                'email' => 'required|email|max:255|unique:users,email,' . $id,
            ]
        );
        
        $user = User::findOrFail($id);

        $user->update([
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
        ]);


        return $user->syncRoles([$this->role]);
    }

    public function delete()
    {
        return $this->user->delete();
    }
}