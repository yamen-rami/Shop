<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('images', 'product_id')) {
            Schema::table('images', function (Blueprint $table) {
                $table->foreignId('product_id')->nullable()->constrained()->cascadeOnDelete();
            });
        }

        DB::table('images')->where('imageable_type', (new Product)->getMorphClass())
            ->whereNull('product_id')->whereIn('imageable_id', DB::table('products')->select('id'))
            ->update(['product_id' => DB::raw('imageable_id')]);
    }

    public function down(): void
    {
        Schema::table('images', function (Blueprint $table) {
            $table->dropConstrainedForeignId('product_id');
        });
    }
};
