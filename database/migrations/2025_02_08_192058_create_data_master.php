<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tamu', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('alamat');
            $table->string('kota');
            $table->string('email');
            $table->string('no_tlpn');
            $table->string('no_identitas');
            $table->integer('jumlah_anak')->default(0);
            $table->integer('jumlah_dewasa')->default(1);
            $table->timestamps();
        });

        Schema::create('keluhan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tamu');
            $table->text('keluhan');
            $table->enum('status_keluhan', ['diproses', 'selesai']);
            $table->timestamps();
        });

        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('no_tlpn');
            $table->string('alamat');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->enum('posisi', [
                'General Manager', 'Asisten Manajer', 'HRD', 'Akuntan',
                'Resepsionis', 'Bellboy', 'Concierge',
                'Room Attendant', 'Housekeeper',
                'Chef', 'Waiter', 'Bartender',
                'Satpam', 'Teknisi', 'IT Support'
            ]);
            $table->decimal('gaji_pokok', 15, 2);
            $table->enum('status_kerja', ['aktif', 'cuti', 'resign'])->default('aktif');
            $table->date('tanggal_bergabung')->nullable();
            $table->enum('shift_kerja', ['pagi', 'siang', 'malam'])->nullable();
            $table->timestamps();
        });

        Schema::create('jenis_kamar', function(Blueprint $table){
            $table->id();
            $table->string('tipe_kamar');
            $table->string('jenis_ranjang');
            $table->timestamps();
        });

        Schema::create('diskon', function (Blueprint $table) {
            $table->id();
            $table->string('kode_diskon')->unique();
            $table->decimal('persentase', 5, 2);
            $table->date('tanggal_mulai');
            $table->date('tanggal_berakhir');
                $table->foreignId('id_jenis_kamar')->constrained('jenis_kamar')->onDelete('cascade');
            $table->timestamps();
        });


        Schema::create('harga', function (Blueprint $table) {
            $table->id();
            $table->string('kode_harga')->unique();
            $table->date('tanggal_mulai');
            $table->date('tanggal_berakhir');
            $table->decimal('persentase_kenaikan_harga', 5, 2);
            $table->foreignId('id_jenis_kamar')->constrained('jenis_kamar')->onDelete('cascade');
            $table->timestamps();
        });

        

        Schema::create('kamar', function (Blueprint $table) {
            $table->id();
            $table->string('no_kamar')->unique();
            $table->enum('status_kamar', ['tersedia', 'terisi', 'perbaikan']);
            $table->foreignId('id_jenis_kamar')->constrained('jenis_kamar')->onDelete('cascade');
            $table->decimal('harga_kamar', 15, 2);
            $table->timestamps();
        });

        


        
        

        

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kamar');
        Schema::dropIfExists('harga');
        Schema::dropIfExists('diskon');
        Schema::dropIfExists('karyawan');
        Schema::dropIfExists('keluhan');
        Schema::dropIfExists('tamu');
    }
};
