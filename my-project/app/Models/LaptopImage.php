<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaptopImage extends Model
{
    use HasFactory;

    protected $table = 'laptop_images';

    protected $fillable = [
        'laptop_id',
        'image_path',
        'sort_order',
    ];

    /**
     * The laptop this image belongs to.
     */
    public function laptop(): BelongsTo
    {
        return $this->belongsTo(Laptop::class, 'laptop_id');
    }

    /**
     * Get the accessible public URL of the image.
     */
    public function getImageUrlAttribute(): string
    {
        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        return asset('storage/' . $this->image_path);
    }
}

