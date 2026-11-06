<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laptop extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'laptop';

    /**
     * Indicates if the model should be timestamped.
     * Disabled — we manually set created_at on create only.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Automatically set created_at when a new record is inserted.
     */
    protected static function booted(): void
    {
        static::creating(function (Laptop $laptop) {
            if (empty($laptop->created_at)) {
                $laptop->created_at = now();
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Section 1: Core Hardware Specification (Required)
        'brand',
        'model',
        'processor_cpu',
        'memory_ram',
        'storage',
        'graphics_gpu',
        'display',
        'price_estimate',
        'image_path',

        // Section 2: Hardware Health & Diagnostics (Nullable)
        'battery_health',
        'storage_health',
        'thermal_performance',

        // Section 3: Physical & Aesthetic Condition (Nullable)
        'physical_condition',

        // Section 4: Input/Output (I/O) & Connectivity (Nullable)
        'ports_connectivity',
        'camera',
        'microphone',
        'audio',
        'keyboard',

        // Section 5: Software & Licensing Status (Nullable)
        'operating_system',
        'is_locks_cleared',
        'is_available',

        // Section 6: Addons (Nullable)
        'addons',

        // Timestamps
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at'              => 'date',
        'price_estimate'          => 'decimal:2',
        'is_locks_cleared' => 'boolean',
        'is_available' => 'boolean',
    ];

    /**
     * Scope a query to only include available laptops.
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Check if laptop has any hardware health diagnostic info.
     */
    public function hasHealthDiagnostics(): bool
    {
        return filled($this->battery_health) 
            || filled($this->storage_health) 
            || filled($this->thermal_performance);
    }

    /**
     * Check if laptop has physical condition info.
     */
    public function hasPhysicalCondition(): bool
    {
        return filled($this->physical_condition);
    }

    /**
     * Check if laptop has any I/O & Connectivity info.
     */
    public function hasIOConnectivity(): bool
    {
        return filled($this->ports_connectivity)
            || filled($this->camera)
            || filled($this->microphone)
            || filled($this->audio)
            || filled($this->keyboard);
    }

    /**
     * Check if laptop has software & licensing info.
     */
    public function hasSoftwareLicensing(): bool
    {
        return filled($this->operating_system) || !is_null($this->is_locks_cleared);
    }

    /**
     * Check if laptop has addons info.
     */
    public function hasAddons(): bool
    {
        return filled($this->addons);
    }

    /**
     * Check if laptop has price estimate.
     */
    public function hasPriceEstimate(): bool
    {
        return !is_null($this->price_estimate) && (float)$this->price_estimate > 0;
    }

    /**
     * Get human-formatted price in Indonesian Rupiah (Rp).
     */
    public function getFormattedPriceAttribute(): ?string
    {
        if (!$this->hasPriceEstimate()) {
            return null;
        }

        return 'Rp ' . number_format((float)$this->price_estimate, 0, ',', '.');
    }

    /**
     * Check if laptop has an associated photo.
     */
    public function hasImage(): bool
    {
        return filled($this->image_path);
    }

    /**
     * Get the accessible public URL of the laptop's photo.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->hasImage()) {
            return null;
        }

        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        return asset('storage/' . $this->image_path);
    }
}

