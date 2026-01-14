<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('publisher_id')->nullable()->constrained()->nullOnDelete();

            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            $table->year('published_year')->nullable();
            $table->enum('edition_type', ['regular', 'limited', 'collector'])->default('regular');
            $table->enum('condition', ['new', 'like_new', 'used', 'discontinued'])->default('new');

            $table->string('series')->nullable();
            $table->unsignedInteger('volume')->nullable();

            $table->decimal('price', 12, 2)->default(0);
            $table->string('cover')->nullable();
            $table->unsignedInteger('stock')->default(0);

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comics');
    }
};
