<div x-data="{ activeTab: 'tab1' }" class="tab-section">
    <!-- Tabs Navigation -->
    <div class="tabs tabs-lifted mb-5 mt-5">
        <button 
            :class="{'tab-active': activeTab === 'tab1'}"
            @click="activeTab = 'tab1'; window.history.pushState({}, '', window.location.pathname)"
            class="tab">Kelola Data Tamu</button>

        <button 
            :class="{'tab-active': activeTab === 'tab2'}"
            @click="activeTab = 'tab2'; window.history.pushState({}, '', window.location.pathname)"
            class="tab">Data Keluhan</button>
    </div>

    <!-- Tabs Content -->
    <div>
        <div x-show="activeTab === 'tab1'">
            <livewire:DataTamu.tamu-index />
        </div>
        
        <div x-show="activeTab === 'tab2'">
            <livewire:Keluhan.keluhan-index />
        </div>
        
    </div>

</div>
