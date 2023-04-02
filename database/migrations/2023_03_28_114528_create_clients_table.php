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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
			$table->string('email')->nullable();
			$table->string('password')->nullable();
			$table->string('name');
			$table->string('country_code', 5)->nullable();
            $table->boolean('is_active')->default(1);
			$table->integer('otp')->nullable();
            $table->timestamp('otp_valid')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
