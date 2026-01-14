<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comic_id')->constrained('comics')->cascadeOnDelete();

            // loại giao dịch: nhập, bán, hoàn trả, điều chỉnh,...
            $table->enum('type', ['import', 'sale', 'return', 'adjustment']);

            // số lượng +/-
            $table->integer('quantity_change');

            // tham chiếu đến đơn hàng (nếu có)
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();

            // ai thực hiện (nhân viên/admin)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};

