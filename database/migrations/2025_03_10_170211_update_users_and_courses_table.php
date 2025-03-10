<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('mente', 'mentor', 'petugas', 'pembimbing') NOT NULL");

        Schema::table('courses', function (Blueprint $table) {
            $table->unsignedBigInteger('pembimbing_id')->nullable()->after('mentor_id');

            $table->foreign('pembimbing_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('mente', 'mentor', 'petugas') NOT NULL");

        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['pembimbing_id']);
            $table->dropColumn('pembimbing_id');
        });
    }
};
