<div>
    <x-dialog-modal wire:model.live="modalUserEdit" submit="edit"> 
        <x-slot name="title">
            Form Edit User
        </x-slot>
    
        <x-slot name="content">
            <div class="grid grid-cols-12 gap-4">

                {{-- Name --}}
                <div class="col-span-12">
                    <x-label for="form.name" value="Name" />
                    <x-input-form wire:model="form.name" id="form.name" type="text" class="mt-1 w-full" required autocomplete="form.name"/>
                    <x-input-form-error for="form.name" class="mt-1" />
                </div>

                {{-- Name --}}
                <div class="col-span-12">
                    <x-label for="form.usernameEdit" value="Username" />
                    <x-input-form wire:model="form.username" id="form.usernameEdit" type="text" class="mt-1 w-full" required autocomplete="form.username"/>
                    <x-input-form-error for="form.username" class="mt-1" />
                </div>

                {{-- Email --}}
                <div class="col-span-12">
                    <x-label for="form.email" value="Email" />
                    <x-input-form wire:model="form.email" id="form.email"  type="email" class="mt-1 w-full" required autocomplete="form.email"/>
                    <x-input-form-error for="form.email" class="mt-1" />
                </div>

                {{-- Role --}}
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
            <x-secondary-button @click="$wire.set('modalUserEdit', false)" wire:loading.attr="disabled">
               Batal
            </x-secondary-button>
    
            <x-button class="ms-3" wire:loading.attr="disabled">
                Update
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <script>
        document.getElementById('form.usernameEdit').addEventListener('input', function (e) {
            this.value = this.value.replace(/\s/g, ''); // Hapus semua spasi
        });
    </script>
</div>