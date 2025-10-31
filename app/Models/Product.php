<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'image',
        'stock',
        'is_available',
    ];

    protected static function booted()
    {
        // Automatically set is_available based on stock when saving
        static::saving(function ($product) {
            if ($product->stock <= 0) {
                $product->is_available = false;
            }
        });
    }

    /**
     * Get all images for this product based on file naming convention
     * Files should be named: {product_id}_1.jpg, {product_id}_2.jpg, etc.
     * Supported formats: jpg, jpeg, png, gif, webp
     */
    public function getImages(): array
    {
        $images = [];
        $directory = public_path('images/products');
        $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!is_dir($directory)) {
            return $images;
        }

        foreach ($extensions as $ext) {
            $pattern = "{$this->id}_*.{$ext}";
            $files = glob($directory . '/' . $pattern);

            foreach ($files as $file) {
                $images[] = '/images/products/' . basename($file);
            }
        }

        // Sort images by sequence number (e.g., 1_1.jpg, 1_2.jpg)
        usort($images, function($a, $b) {
            preg_match('/_(\d+)\./', $a, $matchesA);
            preg_match('/_(\d+)\./', $b, $matchesB);
            $numA = isset($matchesA[1]) ? (int)$matchesA[1] : 0;
            $numB = isset($matchesB[1]) ? (int)$matchesB[1] : 0;
            return $numA - $numB;
        });

        return $images;
    }

    /**
     * Get the first image for this product (for listing pages)
     * Falls back to the image column if no files found
     */
    public function getFirstImage(): string
    {
        $images = $this->getImages();

        if (!empty($images)) {
            return $images[0];
        }

        // Fallback to image column
        return $this->image ?? 'https://picsum.photos/400/400?random=' . $this->id;
    }
}
