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
            $table->boolean('is_main_admin')->default(false)->after('password');
        });

        // Designate existing primary administrator as main admin
        \Illuminate\Support\Facades\DB::table('users')
            ->where('id', 1)
            ->orWhere('email', 'admin@admin.com')
            ->update(['is_main_admin' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_main_admin');
        });
    }
};
