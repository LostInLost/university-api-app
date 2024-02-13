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
        Schema::create('students', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('admin_id');
            $table->foreignUuid('city_id')->nullable();
            $table->string('nim')->unique();
            $table->date('born_date');
            $table->string('name');
            $table->enum('gender', ['L', 'P']);
            $table->text('address');
            $table->string('university');
            $table->timestamps();
            $table->foreign('admin_id')->references('id')->on('admins')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('city_id')->references('id')->on('cities')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
