<?php

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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->references('id')->on('orders');
            $table->decimal('amount', 10, 2);
            $table->string('status', 45);
            $table->string('type', 45);
            $table->foreignIdFor(User::class, 'created_by')->nullable();
            $table->foreignIdFor(User::class, 'updated_by')->nullable();
            $table->timestamps();
        });
        // Dalam contoh ini, kita menggunakan model User sebagai referensi foreign key untuk kolom created_by. Jadi, tidak perlu lagi menentukan tabel referensi secara terpisah, karena Laravel secara otomatis akan menggunakan tabel yang sesuai dengan model yang Anda tentukan
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
