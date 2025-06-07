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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->timestamp('date_start')->nullable();
            $table->timestamp('date_current')->nullable();
            $table->decimal('cash_start', 10, 2)->default(5000.00);
            $table->decimal('cash_current', 10, 2)->default(5000.00);
            $table->decimal('cash_month_expenses', 10, 2)->default(0);
            $table->json('options')->nullable();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
