<?php

use Illuminate\Database\Schema\Blueprint;

class SetupMerchantTables {
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
            $table->timestamps();
            $table->primary('cardID');
        });

        Schema::create($pre . 'card_info', function (Blueprint $table) use ($pre) {
            $table->string('cardID', 32);
            $table->foreign('cardID')->references('cardID')->on($pre . 'cards')->onDelete('restrict')->onUpdate('cascade');
            $table->string('cardName', 128);
            $table->char('cardExpiryMonth', 2);
            $table->char('cardExpiryYear', 2);
            $table->char('cardNumberFirst', 4);
            $table->char('cardNumberLast', 4);
            $table->dateTime('cardAdded');
            $table->timestamps();
            $table->primary('cardID');
        });

        Schema::create($pre . 'log', function (Blueprint $table) {
            $table->increments('id');
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
        Schema::dropIfExists($pre . 'log');
        Schema::dropIfExists($pre . 'card_info');
        Schema::dropIfExists($pre . 'cards');
    }
}
