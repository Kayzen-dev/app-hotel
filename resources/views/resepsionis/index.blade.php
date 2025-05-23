<x-app-layout>
    @if(session('success'))
    <x-slot name="header">
        <div id="success-message" class="px-4 py-3 rounded mb-4">
            <div role="alert" class="alert alert-success">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    </x-slot>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function () {
                const successMessage = document.getElementById('success-message');
                if (successMessage) {
                    successMessage.style.display = 'none';
                }
            }, 3000); // 3000ms = 3 detik
        });
    </script>
    @endif


    <livewire:Dashboard.dash-index/>

    
</x-app-layout>
