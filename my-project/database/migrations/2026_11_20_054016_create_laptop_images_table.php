<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laptop_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laptop_id')->constrained('laptop')->cascadeOnDelete();
            $table->string('image_path', 500);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Migrate any existing laptop image_path into laptop_images table
        if (Schema::hasTable('laptop') && Schema::hasColumn('laptop', 'image_path')) {
            $existing = DB::table('laptop')
                ->whereNotNull('image_path')
                ->where('image_path', '!=', '')
                ->get();

            foreach ($existing as $item) {
                DB::table('laptop_images')->insert([
                    'laptop_id' => $item->id,
                    'image_path' => $item->image_path,
                    'sort_order' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laptop_images');
    }
};
