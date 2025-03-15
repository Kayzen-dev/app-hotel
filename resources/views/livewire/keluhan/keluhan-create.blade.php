<div>

    
    <x-secondary-button @click="$wire.set('modalCreate', true)">
        Tambah Keluhan
    </x-secondary-button>

    <x-dialog-modal wire:model.live="modalCreate" submit="save">
        
        

        <x-slot name="content">
            <div class="max-w-3xl mx-auto p-6 ">
                <h2 class="text-2xl dark:text-black-300 font-semibold mb-4">Tambah Keluhan</h2>

                <form wire:submit.prevent="submit">
                    <div class="mb-5">
                        <label for="name" class="mb-3 block text-base font-medium text-[#07074D]">
                            Nama Tamu
                        </label>
                        <input type="text" wire:model="namaTamu" id="name" placeholder="Masukan Nama Tamu"
                            class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-black outline-none focus:border-[#6A64F1] focus:shadow-md" />
                    </div>
                    <div class="mb-4">
                        <label for="keluhan" class="block text-sm font-medium text-gray-700">Masukan Keluhan</label>
                        <textarea wire:model="keluhan" id="keluhan" rows="4" required class="mt-1 block w-full px-4 py-2 border text-black border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Masukkan keluhan Anda..."></textarea>
                        @error('keluhan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="w-full py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                            Simpan Keluhan
                        </button>
                    </div>
                </form>


                
            </div>
            
        </x-slot>



    </x-dialog-modal>


</div>