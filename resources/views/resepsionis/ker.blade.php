<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ketersediaan Kamar</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.5.0/axios.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .kamar-container { margin-top: 20px; max-width: 500px; }
        .kamar-item { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h2>Daftar Ketersediaan Kamar</h2>
    <div x-data="ketersediaanKamar()" class="kamar-container">
        <template x-for="(kamar, index) in nomor_kamar" :key="index">
            <div class="kamar-item">
                <input type="checkbox" x-model="kamar.status_no_kamar" @change="updateJson()">
                <label x-text="'Kamar ' + kamar.no_kamar"></label>
            </div>
        </template>
        <pre x-text="jsonOutput"></pre> <!-- Menampilkan JSON yang diperbarui -->
    </div>
    <script>
        function ketersediaanKamar() {
            return {
                nomor_kamar: [],
                jsonOutput: '', // Untuk menyimpan JSON yang diperbarui
                async fetchKamar() {
                    try {
                        let response = await axios.get('/resepsionis/nomor-kamar');
                        this.nomor_kamar = response.data.nomor_kamar;
                        this.updateJson(); // Update JSON saat data diambil
                    } catch (error) {
                        console.error('Gagal mengambil data kamar:', error);
                    }
                },
                updateJson() {
                    // Mengubah nomor_kamar menjadi JSON string
                    this.jsonOutput = JSON.stringify({ nomor_kamar: this.nomor_kamar }, null, 2);
                },
                init() {
                    this.fetchKamar();
                }
            };
        }
    </script>
</body>
</html>