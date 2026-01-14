<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('comic_id')->constrained()->cascadeOnDelete();

            $table->decimal('price', 12, 2);
            $table->unsignedInteger('quantity')->default(1);

            $table->timestamps();

            $table->unique(['order_id', 'comic_id']);
        });

    }

    
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
