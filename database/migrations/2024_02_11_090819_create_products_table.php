<?php

use App\Models\Brand;
use App\Models\Categories;
use App\Models\User;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title',200);
            $table->string('slug',400);
            $table->integer('quantity');
            $table->longText('description')->nullable();
            $table->boolean('published')->default(0);
            $table->boolean('in_stock')->default(0);
            $table->decimal('price',10,2);
            $table->foreignIdFor(User::class,'created_by')->nullable(); // ini artinya kita akan membuat created_by yg sekaligus akan berelasi dengan user id. so ini baru dilaravel
            $table->foreignIdFor(User::class,'updated_by')->nullable();
            $table->foreignIdFor(Brand::class,'brand_id')->nullable();
            $table->foreignIdFor(Categories::class,'categories_id')->nullable();
            $table->softDeletes();
            $table->foreignIdFor(User::class,'deleted_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};