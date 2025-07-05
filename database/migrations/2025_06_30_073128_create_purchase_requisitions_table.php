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
        Schema::create('purchase_requisitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requester_id')->constrained('users')->cascadeOnDelete();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent']);
            $table->string('pr_number');
            $table->datetime('required_by_date');
            $table->longText('comment');
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'cancelled', 'completed']);
            $table->foreignId('prepared_by_id')->constrained('users')->cascadeOnDelete()->nullable();
            $table->datetime('prepared_dt')->nullable();
            $table->foreignId('mansor_by_id')->constrained('users')->cascadeOnDelete()->nullable();
            $table->datetime('mansor_dt')->nullable();
            $table->foreignId('gm_by_id')->constrained('users')->cascadeOnDelete()->nullable(); //gm
            $table->datetime('gm_dt')->nullable();
            $table->foreignId('executive_by_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->dateTime('executive_at')->nullable();
            $table->json('attachments');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_requisitions');
    }
};
