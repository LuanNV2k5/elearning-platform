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
        Schema::create('roles', function (Blueprint $table) {
            // BẮT BUỘC: dùng InnoDB để hỗ trợ Foreign Key
            $table->engine = 'InnoDB';

            // Primary key
            $table->id(); // BIGINT UNSIGNED

            // Tên role: admin | teacher | student
            $table->string('name')->unique();

            // Thời gian
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
