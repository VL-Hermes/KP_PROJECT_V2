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
        Schema::create('preset', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');;
            $table->boolean('show_battery_health');
            $table->boolean('show_storage_health');       
            $table->boolean('show_thermal_performance');  
            $table->boolean('show_physical_condition');                
            $table->boolean('show_camera');                            
            $table->boolean('show_microphone');                
            $table->boolean('show_audio');                
            $table->boolean('show_keyboard');
            $table->boolean('show_operating_system');
            $table->boolean('is_available');
            $table->boolean('show_addons');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
