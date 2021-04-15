<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaystackAccessCodeToDirectoryPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directory_payments', function (Blueprint $table) {
            //
            $table->text('paystack_access_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('directory_payments', function (Blueprint $table) {
            //
            $table->dropColumn('paystack_access_code');
        });
    }
}
