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
        Schema::table('laptop', function (Blueprint $table) {
            $table->decimal('price_estimate', 14, 2)->nullable()->after('display');
            $table->string('image_path', 500)->nullable()->after('price_estimate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laptop', function (Blueprint $table) {
            $table->dropColumn(['price_estimate', 'image_path']);
        });
    }
};
