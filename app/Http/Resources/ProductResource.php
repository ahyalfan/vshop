<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'product_images' => $this->whenLoaded('productImage', function () {
                    // Periksa apakah relasi productImage telah dimuat
                if ($this->productImage == null) {
                    return [];
                }
                return ProductImageResource::collection($this->productImage);
            }),
            'categories_id' => $this->categories_id,
            'categories' => new CategoriesResource($this->whenLoaded('categories')),
            'brand_id' => $this->brand_id,
            'brand' => new BrandResource($this->whenLoaded('brand')),

       
        ];
    }
}