<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('product', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->decimal('price', 10, 2);
        $table->unsignedBigInteger('category_id');
        $table->text('description');
        $table->integer('stock');
        $table->string('image')->nullable();
        $table->timestamps();

        // Foreign key constraint
        $table->foreign('category_id')
              ->references('id')
              ->on('category')
              ->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::dropIfExists('product');
}
};
