<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtpVerifiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('otp_verifies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('verifiable_id');
            $table->foreign('verifiable_id')->references('id')->on('users');
            $table->string('phone')->nullable();
            $table->tinyInteger('otp_expired')->default(1);
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('otp_verifies');
    }
}
