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
    Schema::create('exchange_requests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('skill_id')->constrained()->cascadeOnDelete();
        $table->text('message')->nullable();
        $table->enum('status', ['en_attente', 'acceptee', 'refusee'])->default('en_attente');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_requests');
    }
};
