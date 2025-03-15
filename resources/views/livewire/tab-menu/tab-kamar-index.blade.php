<div x-data="{ activeTab: 'tab1' }" class="tab-section">
    <!-- Tabs Navigation -->
    <div class="tabs tabs-lifted mb-5 mt-5">
        <button 
            :class="{'tab-active': activeTab === 'tab1'}"
            @click="activeTab = 'tab1'; window.history.pushState({}, '', window.location.pathname)"
            class="tab">Kelola Kamar</button>

        <button 
            :class="{'tab-active': activeTab === 'tab2'}"
            @click="activeTab = 'tab2'; window.history.pushState({}, '', window.location.pathname)"
            class="tab">Kelola Jenis Kamar</button>
        <button 
            :class="{'tab-active': activeTab === 'tab3'}"
            @click="activeTab = 'tab3'; window.history.pushState({}, '', window.location.pathname)"
            class="tab">Kelola Diskon</button>
        <button 
            :class="{'tab-active': activeTab === 'tab4'}"
            @click="activeTab = 'tab4'; window.history.pushState({}, '', window.location.pathname)"
            class="tab">Kelola Harga</button>
    </div>

    <!-- Tabs Content -->
    <div>
        <div x-show="activeTab === 'tab1'">
            <livewire:Kamar.kamar-index />
        </div>

        <div x-show="activeTab === 'tab2'">
            <livewire:JenisKamar.jenis-kamar-index />
        </div>

        <div x-show="activeTab === 'tab3'">
            <livewire:Diskon.diskon-index />
        </div>

        <div x-show="activeTab === 'tab4'">
            <livewire:Harga.harga-index />
        </div>
    </div>
</div>
