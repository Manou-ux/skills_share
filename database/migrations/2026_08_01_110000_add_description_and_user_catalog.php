<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_skill', function (Blueprint $table) {
            $table->text('description')->nullable()->after('niveau');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->after('slug')->constrained('users')->nullOnDelete();
        });

        Schema::table('skills', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->after('slug')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('user_skill', function (Blueprint $table) {
            $table->dropColumn('description');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropConstrainedForeignId('created_by');
        });

        Schema::table('skills', function (Blueprint $table) {
            $table->dropConstrainedForeignId('created_by');
        });
    }
};
