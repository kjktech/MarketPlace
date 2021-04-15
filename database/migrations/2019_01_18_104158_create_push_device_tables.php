<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePushDeviceTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('fcm_devices', function (Blueprint $table) {
            $table->increments('id');
            $table->text('registration_id');
            $table->boolean('active')->default(1);
            $table->unsignedBigInteger('device_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->timestamps();
        });

        Schema::create('apn_devices', function (Blueprint $table) {
            $table->increments('id');
            $table->text('registration_id');
            $table->boolean('active')->default(1);
            $table->uuid('device_id')->nullable();
            $table->integer('user_id')->nullable();
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
        //
        Schema::dropIfExists('fcm_devices');
        Schema::dropIfExists('apn_devices');
    }
}
