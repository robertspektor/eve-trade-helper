<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->integer('duration');
            $table->boolean('is_buy_order');
            $table->dateTime('issued');
            $table->bigInteger('location_id');
            $table->integer('min_volume');
            $table->bigInteger('order_id');
            $table->double('price');
            $table->enum('range', ['station', 'region', 'solarsystem', 1, 2, 3, 4, 5, 10, 20, 30, 40]);
            $table->integer('system_id');
            $table->integer('type_id');
            $table->integer('volume_remain');
            $table->integer('volume_total');

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
        Schema::dropIfExists('market_orders');
    }
};
