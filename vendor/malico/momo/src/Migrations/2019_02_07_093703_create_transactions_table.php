<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('momo_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tel');
            $table->integer('amount');
            $table->boolean('status')->default(false);
            $table->text('desc');
            $table->text('comment');
            $table->string('transaction_id')->nullable();
            $table->string('reference');
            $table->string('receiver_tel');
            $table->string('operation_type');
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
        Schema::dropIfExists('momo_transactions');
    }
}
