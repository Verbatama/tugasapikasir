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
        Schema::create('juals', function (Blueprint $table) {
            $table->id();
            $table->string('no_bon')->unique();
            $table->foreignId('kasir_id')->constrained('kasirs');
            $table->string('kode_kasir');
            $table->decimal('total', 12, 2);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('bayar', 12, 2);
            $table->decimal('kembali', 12, 2);

            $table->date('tanggal');
            $table->time('waktu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('juals');
    }
};
