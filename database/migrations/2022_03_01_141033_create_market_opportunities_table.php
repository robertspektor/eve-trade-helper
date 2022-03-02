<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_opportunities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('start_hub');
            $table->unsignedBigInteger('end_hub');

            $table->double('start_hub_price');
            $table->double('end_hub_price');
            $table->double('hub2hub_margin');
            $table->timestamps();

            $table->foreign('start_hub')->references('id')->on('locations');
            $table->foreign('end_hub')->references('id')->on('locations');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('market_opportunities');
    }
};
