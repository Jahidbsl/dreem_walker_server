<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->call(CategorySeeder::class);
            $categories = Category::all();
        }

        $sampleImages = [
            'https://images.unsplash.com/photo-1549298916-b41d501d3772',
            'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a',
            'https://images.unsplash.com/photo-1543163521-1bf539c55dd2',
            'https://images.unsplash.com/photo-1560769629-975ec94e6a86',
            'https://images.unsplash.com/photo-1600185365483-26d7a4cc7519',
        ];

        $sizes = ['7', '8', '9', '10', '11'];
        $colors = ['Black', 'Brown', 'White', 'Navy', 'Gray'];

        $productNames = [
            'Chelsea Boots',
            'Classic Sneakers',
            'Running Shoes',
            'Oxford Shoes',
            'Suede Loafers',
            'Canvas Sneakers',
            'Leather Sandals',
            'Hiking Boots',
            'Slip-On Casual',
            'Derby Shoes',
            'High-Top Sneakers',
            'Espadrilles',
        ];

        foreach ($productNames as $name) {
            $product = Product::create([
                'category_id' => $categories->random()->id,
                'name' => $name,
                'description' => fake()->paragraph(3),
            ]);

            // 2-4 variants per product
            $variantCount = rand(2, 4);
            for ($i = 0; $i < $variantCount; $i++) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'size' => $sizes[array_rand($sizes)],
                    'color' => $colors[array_rand($colors)],
                    'price' => fake()->randomFloat(2, 39, 299),
                    'stock' => fake()->numberBetween(0, 200),
                    'image' => $sampleImages[array_rand($sampleImages)] . '?w=800&q=80',
                ]);
            }
        }
    }
}