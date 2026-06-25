<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('parent_id')->nullable()->default(null);
            $table->string('label');
            $table->string('url')->nullable();
            $table->string('type')->default('custom');
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('target')->default('_self');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
