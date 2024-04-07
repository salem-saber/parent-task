<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Project\TransactionModule\Contexts\TransactionStatus;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedDouble('amount', 15, 2)->comment('The amount of money paid by the user');
            $table->char('currency', 3)->comment('The currency of the user in ISO 4217 format');
            $table->string('email')->comment('The email of the parent user');
            $table->string('status')->default(TransactionStatus::AUTHORISED)->comment('The status of the transaction');
            $table->date('transaction_date')->nullable()->comment('The date of the payment');
            $table->string('provider')->comment('The type of the payment provider');
            $table->string('provider_id')->comment('The id of the payment provider');

            $table->timestamps();


            $table->index('email');
            $table->index('status');
            $table->index('transaction_date');
            $table->index('provider');
            $table->index('provider_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
