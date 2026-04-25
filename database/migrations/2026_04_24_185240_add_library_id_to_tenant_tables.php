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
        Schema::table('seats', function (Blueprint $table) {
            $table->foreignId('library_id')->after('id')->constrained('library_settings')->cascadeOnDelete();
        });

        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('library_id')->after('id')->constrained('library_settings')->cascadeOnDelete();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('library_id')->after('id')->constrained('library_settings')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seats', function (Blueprint $table) {
            $table->dropForeign(['library_id']);
            $table->dropColumn('library_id');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['library_id']);
            $table->dropColumn('library_id');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['library_id']);
            $table->dropColumn('library_id');
        });
    }
};
