<?php

namespace Database\Seeders;

use App\Models\Laptop;
use Illuminate\Database\Seeder;

class LaptopSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Fully tested machine (All specs + health diagnostics + peripherals + addons)
        Laptop::create([
            'brand' => 'Lenovo',
            'model' => 'ThinkPad T480',
            'processor_cpu' => 'Intel Core i5-8350U (4 Cores, 8 Threads, up to 3.6 GHz)',
            'memory_ram' => '16 GB DDR4-2400 MHz (Dual Channel)',
            'storage' => '512 GB NVMe PCIe M.2 SSD',
            'graphics_gpu' => 'Intel UHD Graphics 620',
            'display' => '14.0" Full HD (1920x1080) IPS Anti-Glare, 250 nits',
            'price_estimate' => 4500000,
            'battery_health' => 'Good (Dual battery system, 88% overall health, ~5 hrs runtime)',
            'storage_health' => '100% Healthy (SMART OK, 0 bad sectors, low power-on hours)',
            'thermal_performance' => 'Excellent (Fresh thermal paste applied, idle 39°C, load 72°C)',
            'physical_condition' => "Grade A- condition.\nMinor faint scuffs on the rubberized outer lid from normal briefcase carry. Keyboard, trackpad, and screen are immaculate with zero shine or scratches.",
            'ports_connectivity' => '2x USB 3.1 Gen 1, 1x USB-C (Power Delivery), 1x Thunderbolt 3, HDMI 1.4b, RJ-45 Gigabit Ethernet, SD card reader, Intel Dual-Band Wireless-AC 8265 + Bluetooth 4.2',
            'camera' => 'Working (720p HD with ThinkShutter physical privacy slider)',
            'microphone' => 'Working (Dual-array microphone tested clear)',
            'audio' => 'Working (Dolby Audio Premium stereo speakers, clear sound)',
            'keyboard' => 'Working (Legendary ThinkPad tactile keyboard with 2-level white backlight)',
            'operating_system' => 'Windows 11 Pro 64-bit (Digital License Activated, Clean Install)',
            'is_locks_cleared' => true,
            'is_available' => true,
            'addons' => 'Original Lenovo 65W USB-C fast charger + Neoprene protective sleeve',
        ]);

        // 2. Minimal machine (ONLY core hardware filled - ALL optional diagnostics NULL to test dynamic omission)
        Laptop::create([
            'brand' => 'Dell',
            'model' => 'Latitude 7490',
            'processor_cpu' => 'Intel Core i7-8650U (4 Cores, 8 Threads, up to 4.2 GHz)',
            'memory_ram' => '8 GB DDR4-2400 MHz',
            'storage' => '256 GB M.2 SATA SSD',
            'graphics_gpu' => 'Intel UHD Graphics 620',
            'display' => '14.0" Full HD (1920x1080) WVA Anti-Glare',
            'price_estimate' => 3750000,
        ]);

        // 3. Partially tested machine (Battery & Physical condition filled, others omitted)
        Laptop::create([
            'brand' => 'Apple',
            'model' => 'MacBook Air M1 (2020)',
            'processor_cpu' => 'Apple M1 Chip (8-Core CPU with 4 performance & 4 efficiency cores)',
            'memory_ram' => '8 GB Unified Memory',
            'storage' => '256 GB Apple NVMe SSD',
            'graphics_gpu' => '7-Core Apple GPU',
            'display' => '13.3" Retina Display (2560x1600) with True Tone, 400 nits',
            'price_estimate' => 9800000,
            'battery_health' => '91% Maximum Capacity (Normal status, ~12 hrs web browsing)',
            'storage_health' => null,
            'thermal_performance' => 'Silent fanless thermal design (Runs cool, 0dB noise)',
            'physical_condition' => 'Excellent condition (Space Gray). No dents on aluminum corners; tiny cosmetic nick near charging port.',
            'ports_connectivity' => '2x Thunderbolt / USB 4 ports, 3.5mm headphone jack, Wi-Fi 6 (802.11ax), Bluetooth 5.0',
            'camera' => 'Working (720p FaceTime HD camera)',
            'microphone' => null,
            'audio' => null,
            'keyboard' => 'Working (Magic Keyboard with scissor mechanism & Touch ID)',
            'operating_system' => 'macOS Sonoma 14.5 (Factory reset, iCloud logged out)',
            'is_locks_cleared' => true,
            'is_available' => true,
            'addons' => 'Original Apple 30W USB-C Power Adapter + 2m USB-C Charge Cable',
        ]);

        // 4. Gaming / High Performance Laptop (Unavailable / Reserved)
        Laptop::create([
            'brand' => 'ASUS',
            'model' => 'ROG Zephyrus G14 (GA401)',
            'processor_cpu' => 'AMD Ryzen 7 5800HS (8 Cores, 16 Threads, up to 4.4 GHz)',
            'memory_ram' => '16 GB DDR4-3200 MHz (8GB Onboard + 8GB SO-DIMM)',
            'storage' => '1 TB PCIe 3.0 NVMe M.2 SSD',
            'graphics_gpu' => 'NVIDIA GeForce RTX 3060 6GB GDDR6 (Up to 80W)',
            'display' => '14.0" WQHD (2560x1440) 120Hz IPS, 100% DCI-P3, Pantone Validated',
            'price_estimate' => 13500000,
            'battery_health' => 'Good (82% Health, holds 4-5 hrs office work)',
            'storage_health' => '100% SMART Good',
            'thermal_performance' => 'Normal gaming thermals (Dual Arc Flow fans, max 82°C GPU during 3DMark)',
            'physical_condition' => 'Grade B+ (Moonlight White with AniMe Matrix LED lid). All LEDs working.',
            'ports_connectivity' => '1x USB 3.2 Gen 2 Type-C with DisplayPort and Power Delivery, 1x USB 3.2 Gen 2 Type-C, 2x USB 3.2 Gen 1 Type-A, 1x HDMI 2.0b, 3.5mm combo audio jack',
            'camera' => null, // This model had no built-in webcam
            'microphone' => 'Working (3D mic array with AI noise cancelation)',
            'audio' => 'Working (Quad speakers with Dolby Atmos technology)',
            'keyboard' => 'Working (Chiclet keyboard with white backlight)',
            'operating_system' => 'Windows 11 Home 64-bit',
            'is_locks_cleared' => true,
            'is_available' => false, // Currently reserved / sold
            'addons' => 'Original 180W ASUS AC Adapter + External 1080p 60fps USB Webcam',
        ]);

        // 1. Apple MacBook Air (M1) - missing thermal, physical, camera, audio, addons
        Laptop::create([
            'brand' => 'Apple',
            'model' => 'MacBook Air M1 (2020)',
            'processor_cpu' => 'Apple M1 Chip (8-Core CPU with 4 performance & 4 efficiency cores)',
            'memory_ram' => '8 GB Unified Memory',
            'storage' => '256 GB Apple NVMe SSD',
            'graphics_gpu' => '7-Core Apple GPU',
            'display' => '13.3" Retina Display (2560x1600) with True Tone, 400 nits',
            'price_estimate' => 8900000,
            'battery_health' => '91% Maximum Capacity (Normal status, ~12 hrs web browsing)',
            'storage_health' => '99% Health (NVMe SMART OK, 142 cycle/power-on count)',
            'thermal_performance' => null,
            'physical_condition' => null,
            'ports_connectivity' => '2x Thunderbolt / USB 4, 3.5mm headphone jack, Wi-Fi 6 (802.11ax) + Bluetooth 5.0',
            'camera' => null,
            'microphone' => 'Working (Three-mic array with directional beamforming)',
            'audio' => null,
            'keyboard' => 'Working (Magic Keyboard with Touch ID, backlit)',
            'operating_system' => 'macOS Sequoia (Factory reset, ready for setup)',
            'is_locks_cleared' => true,
            'is_available' => true,
            'addons' => null,
        ]);

        // 2. ASUS ROG Zephyrus G14 - missing battery, storage_health, ports_connectivity, microphone, operating_system
        Laptop::create([
            'brand' => 'ASUS',
            'model' => 'ROG Zephyrus G14 GA401',
            'processor_cpu' => 'AMD Ryzen 7 5800HS (8 Cores, 16 Threads, up to 4.4 GHz)',
            'memory_ram' => '16 GB DDR4-3200 MHz (8 GB onboard + 8 GB SO-DIMM)',
            'storage' => '1 TB PCIe 3.0 NVMe M.2 SSD',
            'graphics_gpu' => 'NVIDIA GeForce RTX 3060 Laptop GPU (6 GB GDDR6)',
            'display' => '14.0" WQHD (2560x1440) IPS Anti-Glare, 120Hz, 100% DCI-P3',
            'price_estimate' => 11250000,
            'battery_health' => null,
            'storage_health' => null,
            'thermal_performance' => null,
            'physical_condition' => "Grade B+ condition.\nSmall nick near rear vent grille; palm rest and screen in great shape.",
            'ports_connectivity' => null,
            'camera' => 'Not available (GA401 model has no built-in webcam)',
            'microphone' => null,
            'audio' => 'Working (Quad speakers with Dolby Atmos)',
            'keyboard' => 'Working (Chiclet keyboard with white backlight, minor wear on WASD)',
            'operating_system' => null,
            'is_locks_cleared' => null,
            'is_available' => true,
            'addons' => '180W ASUS power adapter',
        ]);

        // 3. Dell Latitude 5420 - missing price_estimate, storage_health, physical_condition, camera, keyboard, is_locks_cleared
        Laptop::create([
            'brand' => 'Dell',
            'model' => 'Latitude 5420',
            'processor_cpu' => 'Intel Core i7-1185G7 (4 Cores, 8 Threads, up to 4.8 GHz)',
            'memory_ram' => '16 GB DDR4-3200 MHz (Dual Channel)',
            'storage' => '256 GB NVMe PCIe M.2 SSD',
            'graphics_gpu' => 'Intel Iris Xe Graphics',
            'display' => '14.0" Full HD (1920x1080) WVA Anti-Glare, 250 nits',
            'price_estimate' => null,
            'battery_health' => 'Fair (74% health, ~3.5 hrs light office use)',
            'storage_health' => null,
            'thermal_performance' => 'Good (Fan quiet under normal load, peak 78°C)',
            'physical_condition' => null,
            'ports_connectivity' => '2x Thunderbolt 4, 2x USB 3.2 Gen 1, HDMI 2.0, microSD slot, Intel Wi-Fi 6 AX201 + Bluetooth 5.1',
            'camera' => null,
            'microphone' => 'Working (Dual digital microphones tested)',
            'audio' => 'Working (Waves MaxxAudio stereo speakers)',
            'keyboard' => null,
            'operating_system' => 'Windows 11 Pro 64-bit (OEM license)',
            'is_locks_cleared' => null,
            'is_available' => true,
            'addons' => 'Dell 65W USB-C charger',
        ]);

        // 4. HP Pavilion Aero 13 - missing battery, thermal_performance, ports_connectivity, microphone, audio, keyboard, addons
        Laptop::create([
            'brand' => 'HP',
            'model' => 'Pavilion Aero 13-be1000',
            'processor_cpu' => 'AMD Ryzen 5 5625U (6 Cores, 12 Threads, up to 4.3 GHz)',
            'memory_ram' => '8 GB LPDDR4x-4266 MHz (Soldered)',
            'storage' => '512 GB PCIe NVMe M.2 SSD',
            'graphics_gpu' => 'AMD Radeon Graphics',
            'display' => '13.3" WUXGA (1920x1200) IPS Anti-Glare, 16:10, 400 nits',
            'price_estimate' => 6400000,
            'battery_health' => null,
            'storage_health' => '100% Healthy (SMART OK, 0 bad sectors)',
            'thermal_performance' => null,
            'physical_condition' => "Grade A condition.\nClean silver-white chassis with minimal micro-scratches on bottom cover.",
            'ports_connectivity' => null,
            'camera' => 'Working (HP True Vision 720p HD camera)',
            'microphone' => null,
            'audio' => null,
            'keyboard' => null,
            'operating_system' => 'Windows 11 Home Single Language (Activated)',
            'is_locks_cleared' => true,
            'is_available' => true,
            'addons' => null,
        ]);
    }
}

