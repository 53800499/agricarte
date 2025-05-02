<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('products') && Schema::hasTable('users') && Schema::hasTable('categories')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('category_id')->constrained()->onDelete('cascade');
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description');
                $table->decimal('price', 10, 2);
                $table->decimal('stock_quantity', 8, 2)->default(0);
                $table->string('unit')->default('kg'); // kg, unité, etc.
                $table->string('image')->nullable();
                $table->boolean('is_available')->default(true);
                $table->boolean('is_organic')->default(false);
                $table->boolean('is_featured')->default(false);
                $table->json('additional_images')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
