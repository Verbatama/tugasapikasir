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
        Schema::create('detail_juals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barangs')->nullOnDelete();  
            $table->foreignId('jual_id')->constrained('juals')->nullOnDelete();
            $table->string('kode_barang');
            $table->string('no_bon');
            $table->decimal('harga', 12, 2);
            $table->integer('jumlah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_juals');
    }
};
