<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('deposit_orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('client_id');
            $table->uuid('user_id');
            $table->uuid('game_user_id');
            $table->string('chain_id');
            $table->string('product_code');
            $table->string('payment_type');
            $table->decimal('price');
            $table->string('to_address')->nullable();
            $table->string('from_address')->nullable();
            $table->dateTime('expired_at');
            $table->string('tx_id')->nullable();
            $table->tinyInteger('status');
            $table->string('cybavo_order_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('deposit_orders');
    }
};
