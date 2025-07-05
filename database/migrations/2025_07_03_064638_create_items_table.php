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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_requisition_id')->constrained('purchase_requisitions')->cascadeOnDelete();
            $table->string('budget_code')->nullable();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->date('del_date');
            $table->integer('quantity');
            $table->string('unit');
            $table->float('unit_price');
            $table->float('net_price');
            $table->enum('status', ['approved', 'rejected', 'completed', 'received'])->default('approved');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
