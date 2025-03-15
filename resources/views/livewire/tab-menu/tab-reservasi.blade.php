<div x-data="{ activeTab: 'tab1' }" class="tab-section">
    <!-- Tabs Navigation -->
    <div class="tabs tabs-lifted mb-5 mt-5">
        <button 
            :class="{'tab-active': activeTab === 'tab1'}"
            @click="activeTab = 'tab1'; window.history.pushState({}, '', window.location.pathname)"
            class="tab">Data Reservasi</button>

        <button 
            :class="{'tab-active': activeTab === 'tab2'}"
            @click="activeTab = 'tab2'; window.history.pushState({}, '', window.location.pathname)"
            class="tab">Data Riwayat Reservasi</button>
    </div>

    <!-- Tabs Content -->
    <div>
        <div x-show="activeTab === 'tab1'">
            <livewire:Reservasi.reservasi-index />
        </div>
        
        <div x-show="activeTab === 'tab2'">
            <livewire:Reservasi.riwayat-reservasi-index />
        </div>
        
    </div>

</div>
