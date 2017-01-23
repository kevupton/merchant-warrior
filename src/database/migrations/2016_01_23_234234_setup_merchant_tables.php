<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class SetupMerchantTables extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $pre = mw_prefix();
        Schema::create($pre . 'cards', function (Blueprint $table) {
            $table->string('cardID', 32);
            $table->string('cardKey', 64);
            $table->string('ivrCardID', 32);
            $table->string('cardName', 128);
            $table->char('cardExpiryMonth', 2);
            $table->char('cardExpiryYear', 2);
            $table->char('cardNumberFirst', 4);
            $table->char('cardNumberLast', 4);
            $table->dateTime('cardAdded');
            $table->unsignedInteger('user_id')->index()->nullable();
            $table->timestamps();
            $table->primary('cardID');
        });

        Schema::create($pre . 'payments', function (Blueprint $table) use ($pre) {
            $table->increments('id');
            $table->string('cardID', 32)->nullable()->index();
            $table->foreign('cardID')->references('cardID')->on($pre . 'cards')->onDelete('restrict')->onUpdate('cascade');
            $table->double('transactionAmount', 12,2);
            $table->char('transactionCurrency', 3);
            $table->string('transactionProduct', 255);
            $table->string('transactionReferenceID', 16)->nullable();
            $table->string('customerName', 255);
            $table->char('customerCountry', 2);
            $table->string('customerState', 75);
            $table->string('customerCity', 75);
            $table->string('customerAddress', 255);
            $table->string('customerPostCode', 10);
            $table->string('customerPhone', 25)->nullable();
            $table->string('customerEmail', 255)->nullable();
            $table->string('customerIP', 39)->nullable();
            $table->string('custom1', 500)->nullable();
            $table->string('custom2', 500)->nullable();
            $table->string('custom3', 500)->nullable();
            $table->timestamps();
        });

        Schema::create($pre . 'log', function (Blueprint $table) {
            $table->increments('id');
            $table->text('sent')->nullable();
            $table->text('content');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $pre = mw_prefix();
        Schema::dropIfExists($pre . 'payments');
        Schema::dropIfExists($pre . 'log');
        Schema::dropIfExists($pre . 'card_info');
        Schema::dropIfExists($pre . 'cards');
    }
}
