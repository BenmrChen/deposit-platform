<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('deposit_products', function (Blueprint $table) {
            $table->id();
            $table->uuid('client_id');
            $table->json('product_info')->nullable();
            $table->string('product_code');
            $table->string('payment_type');
            $table->decimal('price');
            $table->dateTime('started_at');
            $table->dateTime('ended_at');
            $table->timestamps();

            $table->unique(['client_id', 'product_code']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('deposit_products');
    }
};
