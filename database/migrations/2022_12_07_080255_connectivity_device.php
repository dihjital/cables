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
        Schema::create('connectivity_devices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('zone_id');
            $table->foreign('zone_id')
                ->references('id')->on('zones')
                ->onDelete('cascade');
            $table->unsignedInteger('location_id');
            $table->foreign('location_id')
                ->references('id')->on('locations')
                ->onDelete('cascade');
            $table->string('start');
            $table->string('end');
            $table->unsignedBigInteger('owner_id');
            $table->foreign('owner_id')
                ->references('id')->on('owners')
                ->onDelete('cascade');
            $table->unsignedBigInteger('connectivity_device_type_id');
            $table->foreign('connectivity_device_type_id')
                ->references('id')->on('connectivity_device_types')
                ->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
