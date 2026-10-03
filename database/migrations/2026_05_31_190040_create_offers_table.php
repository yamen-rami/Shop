<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Catagory;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("code")->nullable();
            $table->enum("discount_type" , ["percentage" , "fixed_amount"]);
            $table->enum("type" , ["global","coupon","categories","products"]);
            // $table->foreignIdFor(Catagory::class)->nullable()->constrained()->cascadeOnDelete();
            $table->decimal("discount_value");
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
