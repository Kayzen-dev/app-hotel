<?php

use Illuminate\Support\Facades\Auth;
use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\User;



new #[Layout('layouts.login')] class extends Component
{
    public LoginForm $form;


    /**
     * Handle an incoming authentication request.
     */
    public function login()
    {
        $this->validate();

        $this->form->authenticate();
      

        if (Auth::user()->hasRole('pemilik')) {
            return redirect()->route('pemilik')->with('success', 'Log in Berhasil');
        }

        if (Auth::user()->hasRole('resepsionis')) {
            return redirect()->route('resepsionis')->with('success', 'Log in Berhasil');
        }

        if (Auth::user()->hasRole('karyawan')) {
            return redirect()->route('karyawan')->with('success', 'Log in Berhasil');
        }



        Session::regenerate();

    }
};

?>


<div>



    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />


     


     <div class="w-full max-w-sm mx-auto overflow-hidden bg-white rounded-lg shadow-lg dark:bg-gray-800">
        <div class="px-6 py-4">

            @php
                // dd(session()->all());
            @endphp

            <div class="flex justify-center mx-auto">
                <img class="w-auto h-7 sm:h-8" src="{{ asset('images/logo_hotel.png') }}" style="width: 150px !important; height: 150px !important;" alt="Logo">
            </div>

            <h3 class="mt-3 text-xl font-medium text-center text-gray-600 dark:text-gray-200">Selamat Datang</h3>
            <p class="mt-1 text-center text-gray-500 dark:text-gray-400">Login untuk Masuk ke aplikasi</p>
                @if (session('error_message'))
                <div class="text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error_message') }}
                </div>
            @endif
        
            @if(session('message'))
                <div class="text-yellow-500 px-4 py-3 rounded mb-4">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit="login">
                <div class="w-full mt-4">
                    <input wire:model="form.id_user" id="id_user"  name="id_user" required autofocus class="block w-full px-4 py-2 mt-2 dark:text-gray-100 placeholder-gray-500 bg-white border rounded-lg dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 focus:border-blue-400 dark:focus:border-blue-300 focus:ring-opacity-40 focus:outline-none focus:ring focus:ring-blue-300" type="text" placeholder="Username atau Email" aria-label="Username atau Email" />
                    <x-input-error :messages="$errors->get('form.id_user')" class="mt-2" />
                </div>
                <div class="w-full mt-4 relative">
                    <input 
                        wire:model="form.password" 
                        autocomplete="current-password" 
                        id="password" 
                        class="block w-full px-4 py-2 pr-10 mt-2 dark:text-gray-100 placeholder-gray-500 bg-white border rounded-lg dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 focus:border-blue-400 dark:focus:border-blue-300 focus:ring-opacity-40 focus:outline-none focus:ring focus:ring-blue-300" 
                        type="password" 
                        placeholder="Password" 
                        aria-label="Password" 
                    />
                    <span 
                        id="toggle-password" 
                        class="absolute inset-y-0 right-3 flex items-center cursor-pointer dark:text-white"
                    >
                        <i class="fas fa-eye" id="eye-icon"></i>
                    </span>
                    <x-input-error :messages="$errors->get('form.password')" class="mt-2" />

                </div>
                
                
                <div class="flex items-center justify-end mt-4">
                    <x-primary-button class="ms-3">
                        <span  wire:loading.class="loading loading-spinner loading-md" ></span>
        
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>

            </form>
        </div>                                                                      
    </div>

    <script>
        // Mengubah tipe input password ketika ikon diklik
        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');
    
        togglePassword.addEventListener('click', function() {
            // Toggle password visibility
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
    
            // Ubah ikon berdasarkan kondisi
            if (type === 'password') {
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            } else {
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            }
        });
    </script> 


</div>
