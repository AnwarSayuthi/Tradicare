<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            [
                'product_name' => 'Shampoo - Moisturizing',
                'price' => 12.99,
                'description' => 'Hydrating shampoo for dry hair with argan oil and keratin.',
                'stock_quantity' => 50,
                'category' => 'Hair Care'
            ],
            [
                'product_name' => 'Conditioner - Repair',
                'price' => 14.99,
                'description' => 'Deep conditioning treatment for damaged hair.',
                'stock_quantity' => 45,
                'category' => 'Hair Care'
            ],
            [
                'product_name' => 'Hair Mask - Protein',
                'price' => 19.99,
                'description' => 'Weekly protein treatment to strengthen hair.',
                'stock_quantity' => 30,
                'category' => 'Hair Care'
            ],
            [
                'product_name' => 'Hair Serum - Shine',
                'price' => 24.99,
                'description' => 'Adds shine and reduces frizz for all hair types.',
                'stock_quantity' => 40,
                'category' => 'Hair Care'
            ],
            [
                'product_name' => 'Facial Cleanser - Gentle',
                'price' => 18.99,
                'description' => 'Gentle cleanser for sensitive skin.',
                'stock_quantity' => 35,
                'category' => 'Skin Care'
            ],
            [
                'product_name' => 'Facial Toner - Balancing',
                'price' => 16.99,
                'description' => 'Balances skin pH and tightens pores.',
                'stock_quantity' => 40,
                'category' => 'Skin Care'
            ],
            [
                'product_name' => 'Moisturizer - Hydrating',
                'price' => 22.99,
                'description' => 'Daily moisturizer with hyaluronic acid.',
                'stock_quantity' => 45,
                'category' => 'Skin Care'
            ],
            [
                'product_name' => 'Facial Serum - Vitamin C',
                'price' => 29.99,
                'description' => 'Brightening serum with 15% vitamin C.',
                'stock_quantity' => 30,
                'category' => 'Skin Care'
            ],
            [
                'product_name' => 'Facial Mask - Clay',
                'price' => 15.99,
                'description' => 'Detoxifying clay mask for oily skin.',
                'stock_quantity' => 35,
                'category' => 'Skin Care'
            ],
            [
                'product_name' => 'Nail Polish - Red',
                'price' => 9.99,
                'description' => 'Long-lasting, chip-resistant nail polish.',
                'stock_quantity' => 50,
                'category' => 'Nail Care'
            ],
            [
                'product_name' => 'Nail Strengthener',
                'price' => 12.99,
                'description' => 'Strengthens weak, brittle nails.',
                'stock_quantity' => 40,
                'category' => 'Nail Care'
            ],
            [
                'product_name' => 'Cuticle Oil',
                'price' => 8.99,
                'description' => 'Nourishing oil for dry cuticles.',
                'stock_quantity' => 45,
                'category' => 'Nail Care'
            ],
            [
                'product_name' => 'Body Lotion - Coconut',
                'price' => 17.99,
                'description' => 'Hydrating body lotion with coconut oil.',
                'stock_quantity' => 40,
                'category' => 'Body Care'
            ],
            [
                'product_name' => 'Body Scrub - Coffee',
                'price' => 19.99,
                'description' => 'Exfoliating coffee scrub for smooth skin.',
                'stock_quantity' => 35,
                'category' => 'Body Care'
            ],
            [
                'product_name' => 'Body Oil - Lavender',
                'price' => 21.99,
                'description' => 'Relaxing body oil with lavender essential oil.',
                'stock_quantity' => 30,
                'category' => 'Body Care'
            ],
            [
                'product_name' => 'Makeup Remover',
                'price' => 14.99,
                'description' => 'Gentle makeup remover for all skin types.',
                'stock_quantity' => 45,
                'category' => 'Makeup'
            ],
            [
                'product_name' => 'Foundation - Medium',
                'price' => 24.99,
                'description' => 'Medium coverage foundation for all skin types.',
                'stock_quantity' => 40,
                'category' => 'Makeup'
            ],
            [
                'product_name' => 'Mascara - Volumizing',
                'price' => 18.99,
                'description' => 'Adds volume and length to lashes.',
                'stock_quantity' => 50,
                'category' => 'Makeup'
            ],
            [
                'product_name' => 'Lipstick - Nude',
                'price' => 16.99,
                'description' => 'Creamy nude lipstick for everyday wear.',
                'stock_quantity' => 45,
                'category' => 'Makeup'
            ],
            [
                'product_name' => 'Eyeshadow Palette - Neutral',
                'price' => 29.99,
                'description' => '12 neutral eyeshadow shades for everyday looks.',
                'stock_quantity' => 35,
                'category' => 'Makeup'
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}