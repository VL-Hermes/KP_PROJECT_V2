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
        Schema::create('laptop', function (Blueprint $table) {
            $table->id();

            //Section 1 : Core Hardware Specification *REQUIRED
            $table->string('brand');
            $table->string('model');
            $table->string('processor_cpu');
            $table->string('memory_ram');
            $table->string('storage');
            $table->string('graphics_gpu');
            $table->string('display');

            // Section 2: Hardware Health & Diagnostics *Optiona
            $table->string('battery_health')->nullable();       
            $table->string('storage_health')->nullable();       
            $table->string('thermal_performance')->nullable();  
            //

            // Section 3: Physical & Aesthetic Condition
            
            $table->text('physical_condition')->nullable();     // e.g., "Minor scratch on outer lid, hinges firm, no dead pixels"
            //

            // Section 4: Input/Output (I/O) & Connectivity 
            $table->text('ports_connectivity')->nullable();     // e.g., "All USB ports, HDMI, Wi-Fi, and Bluetooth tested & working"
            $table->string('camera')->nullable();
            $table->string('microphone')->nullable();
            $table->string('audio')->nullable();
            $table->string('keyboard')->nullable();
            //

            // Section 5: Software & Licensing Status            
            $table->string('operating_system')->nullable();     // e.g., "Windows 11 Home 64-bit (Activated)"
            $table->boolean('is_available')->default(false);
            //

            //Section 6: Addons
            $table->string('addons')->nullable();     // e.g., "Windows 11 Home 64-bit (Activated)" 

            $table->timestamps();     // e.g., "Windows 11 Home 64-bit (Activated)" 
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
