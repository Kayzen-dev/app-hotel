<div x-data="{ isModalOpen: @entangle($attributes->wire('model')), imageSrc: @entangle($attributes->wire('image')) }"
     x-show="isModalOpen"
     class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
     x-cloak>
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-3xl max-h-[90vh] overflow-auto">
        <img 
            :src="imageSrc" 
            alt="Preview Bukti Poto" 
            class="w-full h-auto object-contain">
        <button 
            class="mt-4 px-4 py-2 bg-red-500 text-white rounded-md" 
            @click="isModalOpen = false; imageSrc = ''">
            Tutup
        </button>
    </div>
</div>
