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
        Schema::create('cables', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('cable_type_id');
            $table->foreign('cable_type_id')
                ->references('id')
                ->on('cable_types')
                ->onDelete('cascade');
            $table->unsignedBigInteger('start');
            $table->foreign('start')
                ->references('id')
                ->on('connectivity_devices')
                ->onDelete('cascade');
            $table->unsignedBigInteger('end');
            $table->foreign('end')
                ->references('id')->on('connectivity_devices')
                ->onDelete('cascade');
            $table->timestamp('i_time');
            $table->boolean('patch')->default(false);
            $table->text('comment');
            $table->unsignedBigInteger('owner_id');
            $table->foreign('owner_id')
                ->references('id')
                ->on('owners')
                ->onDelete('cascade');
            $table->unsignedBigInteger('cable_purpose_id');
            $table->foreign('cable_purpose_id')
                ->references('id')
                ->on('cable_purposes')
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
