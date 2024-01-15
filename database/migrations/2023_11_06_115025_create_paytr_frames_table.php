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
        Schema::create('paytr_frames', function (Blueprint $table) {
            $table->id();
            $table->string('user_ip')->nullable()->comment('Sipariş verenin IP adresi');
            $table->string('merchant_oid')->nullable()->comment('Sipariş numarası');
            $table->string('email')->nullable()->comment('Sipariş verenin eposta adresi');
            $table->integer('payment_amount')->nullable()->comment('Sipariş tutarı');
            $table->string('currency')->nullable()->comment('Para birimi');
            $table->json('user_basket')->nullable()->comment('Sepet bilgisi');
            $table->tinyInteger('no_installment')->nullable()->comment('1: Tek çekim, 0: Taksitli');
            $table->integer('max_installment')->nullable()->comment('0,2,3,4,5,6,7,8,9,10,11,12 Sıfır (0) gönderilmesi durumunda yürürlükteki en fazla izin verilen taksit geçerli olur');
            $table->string('paytr_token')->nullable()->comment('PayTR token');
            $table->string('user_name')->nullable()->comment('Sipariş verenin adı soyadı');
            $table->string('user_phone')->nullable()->comment('Sipariş verenin telefon numarası');
            $table->longText('user_address')->nullable()->comment('Sipariş verenin adresi');
            $table->text('merchant_ok_url')->nullable()->comment('Ödeme başarılıysa yönlendirilecek sayfa');
            $table->text('merchant_fail_url')->nullable()->comment('Ödeme başarısızsa yönlendirilecek sayfa');
            $table->tinyInteger('test_mode')->nullable()->comment('0: off, 1: on');
            $table->tinyInteger('debug_on')->nullable()->comment('0: off, 1: on');
            $table->integer('timeout_limit')->nullable()->comment('Dakika cinsinden(Gönderilmemesi durumunda 30 dakika olarak tanımlanır)');
            $table->string('lang')->nullable()->comment('tr, en ...');
            $table->boolean('status')->nullable();
            $table->integer('error_code')->nullable()->comment('https://dev.paytr.com/iframe-api/iframe-api-2-adim');
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
