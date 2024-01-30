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
        Schema::create('paytr_return', function (Blueprint $table) {
            $table->id();
            $table->string('user_ip')->nullable()->comment('Sipariş verenin IP adresi');
            $table->string('merchant_oid')->nullable()->comment('Sipariş numarası');
            $table->integer('return_amount')->nullable()->comment('İade Tutarı: Belirtilen sipariş için iade etmek istediğiniz tutar (Ayraç olarak yalnızca bir nokta (.) gönderilmelidir. Örnek: 10.25)');
            $table->boolean('status')->nullable();
            $table->integer('error_code')->nullable()->comment('https://dev.paytr.com/platform-transfer-talebi/transfer-talimatinin-verilmesi');
            $table->string('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paytr_frames');
    }
};
