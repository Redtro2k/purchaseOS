<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dealers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->json('images')->nullable();
            $table->timestamps();
        });

        Schema::table('supplier', function (Blueprint $table) {
            $table->foreignId('dealer_id')->constrained('dealers')->cascadeOnDelete();
        });
        Schema::table('purchase_requisitions', function (Blueprint $table) {
            $table->foreignId('dealer_id')->constrained('dealers')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealers');
    }
};
