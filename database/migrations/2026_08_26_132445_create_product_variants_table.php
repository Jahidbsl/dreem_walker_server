<?php

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
        Schema::create('product_variants', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained()->onDelete('cascade'); // প্রোডাক্ট ডিলিট হলে ভ্যারিয়েন্টও ডিলিট হবে
    $table->string('size')->nullable();   // যেমন: S, M, L, XL
    $table->string('color')->nullable();  // যেমন: Red, Blue, Black
    $table->decimal('price', 10, 2);      // ভ্যারিয়েন্ট অনুযায়ী দাম কমবেশি হতে পারে
    $table->integer('stock')->default(0); // স্টক পরিমাণ
    $table->string('image')->nullable();  // নির্দিষ্ট কালার বা ভ্যারিয়েন্টের ছবি
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
