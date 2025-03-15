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
        Schema::create('reservasi', function (Blueprint $table) {
            $table->id();
            $table->string('no_reservasi')->unique();
            $table->foreignId('id_tamu')->constrained('tamu')->onDelete('cascade');
            $table->date('tanggal_check_in');
            $table->date('tanggal_check_out');
            $table->integer('jumlah_kamar');
            $table->decimal('total_harga', 15, 2);
            $table->decimal('denda', 15, 2)->default(0);
            $table->enum('status_reservasi', ['dipesan', 'check_in', 'check_out', 'selesai','batal'])->default('dipesan');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });


        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_reservasi')->constrained('reservasi')->onDelete('cascade');
            $table->foreignId('id_kamar')->constrained('kamar')->onDelete('cascade');
            $table->foreignId('id_diskon')->nullable()->constrained('diskon')->onDelete('set null');
            $table->foreignId('id_harga')->nullable()->constrained('harga')->onDelete('set null');
            $table->decimal('harga_kamar', 15, 2);
            $table->decimal('harga_akhir', 15, 2);
            $table->integer('jumlah_malam');
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });

        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_reservasi')->constrained('reservasi')->onDelete('cascade');
            $table->foreignId('id_user')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('jumlah_pembayaran', 15, 2);
            $table->decimal('kembalian', 15, 2)->default(0);
            $table->timestamps();
        });


        // Schema::create('riwayat', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('id_reservasi')->nullable()->constrained('reservasi')->onDelete('set null');
        //     $table->foreignId('id_pembayaran')->nullable()->constrained('pembayaran')->onDelete('set null');
        //     $table->string('no_reservasi')->unique();
        //     $table->string('nama_tamu');
        //     $table->decimal('persentase_diskon', 5, 2)->nullable();
        //     $table->decimal('persentase_kenaikan_harga', 5, 2)->nullable();
        //     $table->string('kamar'); // Format: "Tipe Kamar - Jenis Ranjang"
        //     $table->decimal('jumlah_pembayaran', 15, 2)->nullable();
        //     $table->decimal('kembalian', 15, 2)->nullable();
        //     $table->string('resepsionis')->nullable();
        //     $table->date('tanggal_check_in');
        //     $table->date('tanggal_check_out');
        //     $table->integer('jumlah_hari');
        //     $table->decimal('total_harga', 15, 2);
        //     $table->decimal('denda', 15, 2)->nullable();
        //     $table->string('status_reservasi')->default('selesai');
        //     $table->text('keterangan')->nullable();
        //     $table->timestamps();
        // });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
        Schema::dropIfExists('reservasi');
        Schema::dropIfExists('pesanan');
    }
};
