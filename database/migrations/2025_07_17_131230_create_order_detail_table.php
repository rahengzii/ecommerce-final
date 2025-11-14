<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('order_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id'); // Foreign key to order table (id, not order_id string)
            $table->unsignedBigInteger('product_id'); // Foreign key to product table
            $table->string('product_name');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('order_id')
                  ->references('id')
                  ->on('order')
                  ->onDelete('cascade'); // Delete order details when order is deleted

            $table->foreign('product_id')
                  ->references('id')
                  ->on('product')
                  ->onDelete('restrict'); // Prevent product deletion if in order history

            // Indexes for better performance
            $table->index('order_id');
            $table->index('product_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_detail');
    }
};
