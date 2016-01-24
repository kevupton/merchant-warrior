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

        Schema::create(mw_prefix() . 'cards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cardID', 32)->unique();
            $table->string('cardKey', 64);
            $table->string('ivrCardID', 32);
            $table->timestamps();
        });

        Schema::create(mw_prefix() . 'log', function (Blueprint $table) {
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
        Schema::dropIfExists(mw_prefix() . 'cards');
        Schema::dropIfExists(mw_prefix() . 'log');
    }
}
