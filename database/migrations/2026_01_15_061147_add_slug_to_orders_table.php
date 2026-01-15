<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add the slug column without unique constraint
        Schema::table('orders', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('id');
        });

        // Generate unique slugs for existing orders
        $orders = DB::table('orders')->get();
        foreach ($orders as $order) {
            DB::table('orders')
                ->where('id', $order->id)
                ->update([
                    'slug' => 'order-' . $order->id . '-' . Str::random(8)
                ]);
        }

        // Now add the unique constraint
        Schema::table('orders', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Check if index exists before dropping
            $indexes = DB::select("SHOW INDEXES FROM orders WHERE Key_name = 'orders_slug_unique'");
            if (!empty($indexes)) {
                $table->dropUnique(['slug']);
            }
            $table->dropColumn('slug');
        });
    }
};