<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->foreignId('second_metal_id')->nullable()->after('metal_id')
                  ->constrained('metals')->nullOnDelete();
            $table->string('colour', 50)->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropForeign(['second_metal_id']);
            $table->dropColumn(['second_metal_id', 'colour']);
        });
    }
};
