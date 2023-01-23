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
        Schema::create('location_zones', function (Blueprint $table) {
            $table->integer('location_id')->unsigned();
            $table->integer('zone_id')->unsigned();
            $table->foreign('location_id')->references('id')->on('locations')
                ->onDelete('cascade');
            $table->foreign('zone_id')->references('id')->on('zones')
                ->onDelete('cascade');
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
        Schema::dropIfExists('location_zones');
    }
};
