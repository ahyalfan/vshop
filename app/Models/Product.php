<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasFactory,SoftDeletes,HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'published',
        'inStock',
        'price',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Get the options for generating the slug.
     */
    // kita coba menggunakan laravel sluggable untuk membuat slug otomatis
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
        // akan otomatis sesuai title tetapi akan menjadi slug
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

     /**
     * Get the route key for the model.
     *
     * @return string
     */
    // jika mau menggambil untuk router
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // relasi productimage one to many
    public function productImage():HasMany
    {
        return $this->hasMany(ProductImage::class,'product_id','id');
    }

    // relasi categories dan brand one to many
    // sebagai many
    public function categories():BelongsTo
    {
        return $this->belongsTo(Categories::class,'categories_id','id');
    }
    public function brand():BelongsTo
    {
        return $this->belongsTo(Brand::class,'brand_id','id');
    }

    public function cartItem():HasMany
    {
        return $this->hasMany(CartItem::class,'product_id','id');
    }

    //filter logic for price or categories or brands 

    public function scopeFiltered(Builder $quary)  { //ini method buat filter
        $quary
        // request ini diambil dari request http
        ->when(request()->has('brands'), function (Builder $q)  {
            $q->whereIn('brand_id',request('brands'));
        })
        ->when(request()->has('categories'), function (Builder $q)  {
            $q->whereIn('categories_id',request('categories'));
        })
        ->when(request('prices'), function(Builder $q)  {
            $q->whereBetween('price',[
                request('prices.from',0),
                request('prices.to', 100000),
            ]);
        });
        
    }
}
