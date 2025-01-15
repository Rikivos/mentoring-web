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
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'status', 'attendance_time']);
        });

        Schema::create('attendance_users', function (Blueprint $table) {
            $table->id('attendance_user_id');
            $table->unsignedBigInteger('attendance_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['hadir', 'tidak hadir', 'izin']);
            $table->timestamps();

            $table->foreign('attendance_id')->references('attendance_id')->on('attendances')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_users');

        Schema::table('attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['hadir', 'tidak hadir', 'izin']);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
