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
        Schema::table('users', function (Blueprint $table) {
            $table->string('specialty')->nullable()->after('email_verified_at');
            $table->text('description')->nullable()->after('specialty');
            $table->string('level')->nullable()->after('description');
            $table->integer('badge_count')->default(0)->after('level');
            $table->enum('user_type', ['admin', 'mentor', 'student'])->default('student')->after('badge_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('specialty');
            $table->dropColumn('description');
            $table->dropColumn('level');
            $table->dropColumn('user_type');
            $table->dropColumn('badge_count');
        });
    }
};
