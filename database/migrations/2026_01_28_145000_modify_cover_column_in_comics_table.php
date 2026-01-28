<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Thay đổi cột cover từ string sang longText để lưu ảnh dạng Base64
     */
    public function up(): void
    {
        Schema::table('comics', function (Blueprint $table) {
            $table->longText('cover')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comics', function (Blueprint $table) {
            $table->string('cover')->nullable()->change();
        });
    }
};
