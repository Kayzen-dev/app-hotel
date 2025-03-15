<div>
    <livewire:Kamar.kamar-create/>
    <livewire:Kamar.kamar-edit/>
    <livewire:Kamar.kamar-delete/>
    <livewire:Kamar.kamar-table/>
</div>

<script>
        document.getElementById("no_kamar").addEventListener("input", function () {
        this.value = this.value.toUpperCase().replace(/\s/g, ""); 
    });


</script>
