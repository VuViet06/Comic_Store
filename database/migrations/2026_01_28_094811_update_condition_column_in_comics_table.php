<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cập nhật giá trị cũ sang giá trị mới
        DB::statement("UPDATE comics SET `condition` = 'in_stock' WHERE `condition` IN ('new', 'like_new', 'used')");
        DB::statement("UPDATE comics SET `condition` = 'out_of_stock' WHERE `condition` = 'discontinued'");
        
        // Thay đổi ENUM
        DB::statement("ALTER TABLE comics MODIFY COLUMN `condition` ENUM('in_stock', 'coming_soon', 'out_of_stock') DEFAULT 'in_stock'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback về giá trị cũ
        DB::statement("ALTER TABLE comics MODIFY COLUMN `condition` ENUM('new', 'like_new', 'used', 'discontinued') DEFAULT 'new'");
        
        DB::statement("UPDATE comics SET `condition` = 'new' WHERE `condition` = 'in_stock'");
        DB::statement("UPDATE comics SET `condition` = 'discontinued' WHERE `condition` = 'out_of_stock'");
    }
};
