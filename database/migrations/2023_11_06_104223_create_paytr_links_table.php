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
        Schema::create('paytr_links', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->comment('Ürün adı');
            $table->string('price')->nullable()->comment('Ürün fiyatı');
            $table->string('currency')->nullable()->comment('TL, EUR, USD, GBP, JPY, RUB, CHF');
            $table->string('max_installment')->nullable()->comment('Maksimum taksit sayısı');
            $table->string('lang')->nullable()->comment('tr, en');
            $table->tinyInteger('get_qr')->nullable()->comment('0: off, 1: on');
            $table->integer('link_type')->nullable();
            $table->string('paytr_token')->nullable()->comment('Mağaza token');
            $table->integer('min_count')->nullable()->comment('Minimum satın alma adedi');
            $table->integer('max_count')->nullable()->comment('Maksimum satın alma adedi');
            $table->string('email')->nullable()->comment('Sipariş verenin eposta adresi');
            $table->integer('pft')->nullable()->comment('Paytr tarafından tanımlanan ürün numarası');
            $table->dateTime('expiry_date')->nullable()->comment('Ürünün son kullanma tarihi');
            $table->text('callback_link')->nullable()->comment('Geri dönüş linki');
            $table->string('callback_id')->nullable()->comment('Geri dönüş id');
            $table->tinyInteger('debug_on')->nullable()->comment('0: off, 1: on');
            $table->integer('status')->nullable();
            $table->string('error_code')->nullable();
            $table->string('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paytr_orders');
    }
};
