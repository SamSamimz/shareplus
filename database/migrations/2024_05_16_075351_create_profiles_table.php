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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('bio')->nullable();
            $table->string('work_at')->nullable();
            $table->date('birthdate')->nullable();
            $table->integer('mobile')->nullable();
            $table->string('address')->nullable();
            $table->text('image')->nullable();
            $table->text('facebook')->nullable();
            $table->text('github')->nullable();
            $table->text('linkedin')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
