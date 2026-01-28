<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comic;
use App\Models\InventoryTransaction;
use App\Models\Order;
use App\Models\Publisher;
use App\Models\Shipment;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        //  Category::factory(10)->create();
        //  Order::factory(10)->create();
        //  Publisher::factory(5)->create();
        //  Comic::factory(10)->create();
        //  InventoryTransaction::factory(20)->create();
        //  Shipment::factory(10)->create();
        //  Voucher::factory(5)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
