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
        Schema::create('paytr_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('user_ip')->nullable()->comment('Sipariş verenin IP adresi');
            $table->string('merchant_oid')->nullable()->comment('Sipariş numarası');
            $table->string('email')->nullable()->comment('Sipariş verenin eposta adresi');
            $table->integer('payment_amount')->nullable()->comment('Sipariş tutarı');
            $table->string('paytr_token')->nullable()->comment('PayTR token');
            $table->string('user_name')->nullable()->comment('Sipariş verenin adı soyadı');
            $table->string('user_phone')->nullable()->comment('Sipariş verenin telefon numarası');
            $table->integer('tc_no_last5')->nullable()->comment('Sipariş verenin TC kimlik numarasının son 5 hanesi');
            $table->string('bank')->comment('isbank, akbank, denizbank, finansbank,halkbank, ptt, teb, vakifbank, yapikredi,ziraat')->nullable();
            $table->tinyInteger('test_mode')->nullable()->comment('Test modu');
            $table->tinyInteger('debug_on')->nullable()->comment('Debug modu');
            $table->integer('timeout_limit')->nullable()->comment('Dakika cinsinden(Gönderilmemesi durumunda 30 dakika olarak tanımlanır)');
            $table->integer('status')->nullable()->comment('https://dev.paytr.com/havale-eft-iframe-api/havale-eft-iframe-api-2-adim');
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
        Schema::dropIfExists('paytr_transfers');
    }
};
