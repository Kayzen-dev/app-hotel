<div>
    <x-button @click="$wire.set('modalUserCreate', true)">
        Tambah User Baru
    </x-button>

    <a href="{{ route('export.user') }}" class="btn btn-sm inline-flex items-center px-4 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
        EXPORT EXCEL
    </a>

    <a href="{{ route('export-users-pdf') }}" class="btn btn-sm inline-flex items-center px-4 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
        EXPORT PDF
    </a>



    <x-dialog-modal wire:model.live="modalUserCreate" submit="save">
        <x-slot name="title">
            Tambah User
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-12 gap-4">
                {{-- Name --}}
                <div class="col-span-12">
                    <x-label for="form.name" value="Nama User" />
                    <x-input-form wire:model="form.name" id="form.name" type="text" class="mt-1 w-full" required autocomplete="form.name"/>
                    <x-input-form-error for="form.name" class="mt-1" />
                </div>

                {{-- username --}}
                <div class="col-span-12">
                    <x-label for="form.username" value="username" />
                    <x-input-form wire:model="form.username" 
                    id="form.username" type="text" class="mt-1 w-full" required autocomplete="form.username"/>
                    <x-input-form-error for="form.username" class="mt-1" />
                </div>

                {{-- Email --}}
                <div class="col-span-12">
                    <x-label for="form.email" value="Email" />
                    <x-input-form wire:model="form.email" id="form.email" type="email" class="mt-1 w-full" required autocomplete="form.email"/>
                    <x-input-form-error for="form.email" class="mt-1" />
                </div>

                {{-- Password --}}
                <div class="col-span-12">
                    <x-label for="form.password" value="Password" />
                    <x-input-form wire:model="form.password" id="form.password"  type="password" class="mt-1 w-full" required autocomplete="form.password"/>
                    <x-input-form-error for="form.password" class="mt-1" />
                </div>

                {{-- Confirm Password --}}
                <div class="col-span-12">
                    <x-label for="form.confirmPassword" value="Konfirmasi Password" />
                    <x-input-form wire:model="form.confirmPassword" id="form.confirmPassword"  type="password" class="mt-1 w-full" required autocomplete="form.confirmPassword"/>
                    <x-input-form-error for="form.confirmPassword" class="mt-1" />
                </div>

                {{-- Role Selection --}}
                <div class="col-span-12">
                    <x-label for="form.role" value="Role" />
                    <select wire:model="form.role" id="form.role" required class="select select-bordered mt-1 w-full text-white">
                        <option value="">Pilih Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role }}">{{ $role }}</option>
                        @endforeach
                    </select>
                    <x-input-form-error for="form.role" class="mt-1" />
                </div>

                
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button @click="$wire.set('modalUserCreate', false)" wire:loading.attr="disabled">
               Batal
            </x-secondary-button>

            <x-button class="ms-3" wire:loading.attr="disabled">
                Simpan
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <script>
        document.getElementById('form.username').addEventListener('input', function (e) {
            this.value = this.value.replace(/\s/g, ''); // Hapus semua spasi
        });
    </script>
</div>