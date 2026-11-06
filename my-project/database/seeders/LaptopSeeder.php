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
    }
}

