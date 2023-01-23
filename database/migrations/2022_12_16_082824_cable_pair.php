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
        Schema::create('cable_pairs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conn_dev_id');
            $table->foreign('conn_dev_id')
                ->references('id')
                ->on('connectivity_devices')
                ->onDelete('cascade');
            $table->string('conn_point');
            $table->unsignedBigInteger('cable_id');
            $table->foreign('cable_id')
                ->references('id')
                ->on('cables')
                ->onDelete('cascade');
            $table->unsignedBigInteger('cable_pair_status_id');
            $table->foreign('cable_pair_status_id')
                ->references('id')
                ->on('cable_pair_statuses')
                ->onDelete('cascade');
            $table->timestamps();
            $table->unique(['conn_dev_id', 'conn_point', 'cable_id']);
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
