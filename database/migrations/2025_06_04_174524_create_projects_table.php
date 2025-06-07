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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->decimal('budget', 10, 2)->nullable();
            $table->unsignedSmallInteger('complexity')->default(0);
            $table->boolean('completed')->default(false);
            $table->timestamp('completed_at')->nullable();

            $table->unsignedBigInteger('seller_id')->nullable();
            $table->foreign('seller_id')->references('id')->on('sellers')->onDelete('cascade');
            $table->unsignedSmallInteger('seller_xp')->default(0);
            $table->unsignedSmallInteger('generation_progress')->default(0);
            $table->timestamp('generation_started_at')->nullable();
            $table->timestamp('generation_completed_at')->nullable();
            $table->boolean('generation_completed')->default(false);

            $table->unsignedBigInteger('developer_id')->nullable();
            $table->foreign('developer_id')->references('id')->on('developers')->onDelete('cascade');
            $table->unsignedSmallInteger('developer_xp')->default(0);
            $table->unsignedSmallInteger('development_progress')->default(0);
            $table->timestamp('development_started_at')->nullable();
            $table->timestamp('development_completed_at')->nullable();
            $table->boolean('development_completed')->default(false);

            $table->unsignedBigInteger('game_id')->nullable();
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
