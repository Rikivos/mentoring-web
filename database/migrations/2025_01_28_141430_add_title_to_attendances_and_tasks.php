<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('title')->after('deadline')->nullable()->comment('Title of the attendance');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->string('title')->after('description')->nullable()->comment('Title of the task');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('title');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('title');
        });
    }
};
