<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\SlugOptions;
use Spatie\Sluggable\HasSlug;

class Brand extends Model
{
    use HasFactory,HasSlug;
    protected $fillable = [
        "name",
        "slug",
    ];

    /**
     * Get the options for generating the slug.
     */
    // kita coba menggunakan laravel sluggable untuk membuat otomatis slug
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function product():HasMany
    {
        return $this->hasMany(Product::class,"brand_id","id");
    }
}
