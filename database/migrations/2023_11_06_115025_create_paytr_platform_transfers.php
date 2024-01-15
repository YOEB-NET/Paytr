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
        Schema::create('paytr_platform_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('user_ip')->nullable()->comment('Sipariş verenin IP adresi');
            $table->string('merchant_oid')->nullable()->comment('Sipariş numarası');
            $table->string('trans_id')->nullable()->comment('Satıcıya yapılacak bu ödemenin takibi için benzersiz takip numarası	');
            $table->integer('submerchant_amount')->nullable()->comment('Satıcıya yapılacak ödeme tutarı:Satıcıya bu sipariş için ödenecek tutarın 100 ile çarpılmış hali');
            $table->integer('total_amount')->nullable()->comment('Toplam ödeme tutarı: Siparişe ait toplam ödeme tutarının 100 ile çarpılmış hali	');
            $table->longText('transfer_name')->nullable()->comment('Satıcının banka hesabı için ad soyad/ünvanı');
            $table->longText('transfer_iban')->nullable()->comment('Satıcının banka hesabı IBAN numarası');
            $table->string('paytr_token')->nullable()->comment('PayTR token');
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
