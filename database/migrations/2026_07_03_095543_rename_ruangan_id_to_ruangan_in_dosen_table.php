<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dosen', function (Blueprint $table) {
            $table->dropForeign(['ruangan_id']);
        });

        Schema::table('dosen', function (Blueprint $table) {
            $table->dropColumn('ruangan_id');
        });

        Schema::table('dosen', function (Blueprint $table) {
            $table->string('ruangan')->nullable()->after('nidn');
        });
    }

    public function down(): void
    {
        Schema::table('dosen', function (Blueprint $table) {
            $table->dropColumn('ruangan');
        });

        Schema::table('dosen', function (Blueprint $table) {
            $table->unsignedBigInteger('ruangan_id')->nullable()->after('nidn');
        });
    }
};