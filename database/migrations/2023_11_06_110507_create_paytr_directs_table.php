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
        Schema::create('paytr_directs', function (Blueprint $table) {
            $table->id();
            $table->string('merchant_id')->nullable()->comment('Mağaza numarası');
            $table->string('paytr_token')->nullable()->comment('Mağaza token'); 
            $table->string('user_ip')->nullable()->comment('Sipariş verenin IP adresi');
            $table->string('merchant_oid')->nullable()->comment('Sipariş numarası');
            $table->string('email')->nullable()->comment('Sipariş verenin eposta adresi');
            $table->string('payment_type')->comment('Card: Kredi kartı, Wire: Havale, EFT, QrCode: QR Kod')->nullable();
            $table->integer('payment_amount')->comment('Ödeme tutarı')->nullable();
            $table->integer('installment_count')->comment('Taksit sayısı')->nullable();
            $table->string('card_type')->comment('advantage, axess, combo, bonus, cardfinans, maximum, paraf, world, saglamkart')->nullable();
            $table->string('currency')->comment('TL, EUR, USD, GBP, JPY, RUB, CHF')->nullable();
            $table->string('client_lang')->comment('tr, en')->nullable();
            $table->tinyInteger('test_mode')->comment('0: off, 1: on')->nullable();
            $table->tinyInteger('non_3d')->comment('0: off, 1: on')->nullable();
            $table->tinyInteger('non3d_test_failed')->comment('0: off, 1: on')->nullable();
            $table->string('cc_owner')->comment('Kart sahibi')->nullable();
            $table->string('card_number')->comment('Kart numarası')->nullable();
            $table->string('expiry_month')->comment('Kart son kullanma ayı')->nullable();
            $table->string('expiry_year')->comment('Kart son kullanma yılı')->nullable();
            $table->text('merchant_ok_url')->comment('Başarılı ödeme sonrası yönlendirilecek sayfa')->nullable();
            $table->text('merchant_fail_url')->comment('Başarısız ödeme sonrası yönlendirilecek sayfa')->nullable();
            $table->string('user_name')->comment('Sipariş verenin adı')->nullable();
            $table->longText('user_address')->comment('Sipariş verenin adresi')->nullable();
            $table->string('user_phone')->comment('Sipariş verenin telefon numarası')->nullable();
            $table->json('user_basket')->comment('Sepet bilgisi')->default('[]');
            $table->tinyInteger('debug_on')->comment('0: off, 1: on')->nullable();
            $table->tinyInteger('sync_mode')->comment('0: off, 1: on')->nullable();    
            $table->integer('status')->nullable()->comment('https://dev.paytr.com/direkt-api/direkt-api-2-adim');
            $table->string('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paytr_direct_orders');
    }
};
