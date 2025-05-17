<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'product_name' => 'Habbatus Sauda',
                'price' => 35.00,
                'description' => 'Black seed capsules for immunity and general health',
                'stock_quantity' => 30,
                'category' => 'Supplements',
                'product_image' => 'products/1.jpg',
                'active' => true
            ],
            [
                'product_name' => 'Kacip Fatimah',
                'price' => 39.00,
                'description' => 'Supports women’s hormonal balance and energy',
                'stock_quantity' => 45,
                'category' => 'Supplements',
                'product_image' => 'products/2.jpg',
                'active' => true
            ],
            [
                'product_name' => 'Manjakani Capsule',
                'price' => 36.00,
                'description' => 'Supports female reproductive health and hormonal balance',
                'stock_quantity' => 35,
                'category' => 'Supplements',
                'product_image' => 'products/3.jpg',
                'active' => true
            ],
            [
                'product_name' => 'Pegaga Capsule',
                'price' => 30.00,
                'description' => 'Enhances cognitive function and blood circulation',
                'stock_quantity' => 20,
                'category' => 'Supplements',
                'product_image' => 'products/4.jpg',
                'active' => true
            ],
            [
                'product_name' => 'Herba Asmak Oil',
                'price' => 29.00,
                'description' => 'Relieves cough, asthma, and body aches',
                'stock_quantity' => 35,
                'category' => 'Oil & Balms',
                'product_image' => 'products/5.jpg',
                'active' => true
            ],
            [
                'product_name' => 'Minyak Bidara Oil',
                'price' => 25.00,
                'description' => 'May help relieve joint pain, muscle inflammation, and enhance relaxation',
                'stock_quantity' => 40,
                'category' => 'Oil & Balms',
                'product_image' => 'products/6.jpg',
                'active' => true
            ],
            [
                'product_name' => 'Gamat Balm',
                'price' => 27.00,
                'description' => 'Sea cucumber-based balm for reduces discomfort from arthritis, backaches, or sprains',
                'stock_quantity' => 45,
                'category' => 'Oil & Balms',
                'product_image' => 'products/7.jpg',
                'active' => true
            ],
            [
                'product_name' => 'Eucalyptus Oil',
                'price' =>  22.00,
                'description' => 'Soothing oil for congestion, massage, and warmth',
                'stock_quantity' => 30,
                'category' => 'Oil & Balms',
                'product_image' => 'products/8.jpg',
                'active' => true
            ],
            [
                'product_name' => 'Misai Kucing Tea',
                'price' => 18.00,
                'description' => 'Helps with detox and urinary health',
                'stock_quantity' => 35,
                'category' => 'Baverages',
                'product_image' => 'products/9.jpg',
                'active' => true
            ],
            [
                'product_name' => 'Tongkat Ali Coffee',
                'price' => 20.00,
                'description' => 'Herbal coffee to boost energy and alertness',
                'stock_quantity' => 20,
                'category' => 'Baverages',
                'product_image' => 'products/10.jpg',
                'active' => true
            ],
            [
                'product_name' => 'Dates & Raisin Juice',
                'price' => 20.00,
                'description' => 'Natural tonic to increase energy and immunity',
                'stock_quantity' => 35,
                'category' => 'Baverages',
                'product_image' => 'products/11.jpg',
                'active' => true
            ],
            [
                'product_name' => 'Ginger Tea',
                'price' => 19.00,
                'description' => 'Traditional tea for digestion and warmth',
                'stock_quantity' => 25,
                'category' => 'Baverages',
                'product_image' => 'products/12.jpg',
                'active' => true
            ],
            [
                'product_name' => 'Ayurvedic Ubtan Scrub',
                'price' => 12.00,
                'description' => 'Gently removes dead skin cells, leaving the skin smooth and moisturized',
                'stock_quantity' => 20,
                'category' => 'Body Care',
                'product_image' => 'products/13.jpg',
                'active' => true
            ],
            [
                'product_name' => 'Aloe Vera Shampoo',
                'price' => 19.00,
                'description' => 'Herbal shampoo for scalp nourishment and hair strength',
                'stock_quantity' => 35,
                'category' => 'Body Care',
                'product_image' => 'products/14.jpg',
                'active' => true
            ],
            [
                'product_name' => 'Herbal Body Oil',
                'price' => 21.00,
                'description' => 'Removes dead skin and refreshes the body',
                'stock_quantity' => 30,
                'category' => 'Body Care',
                'product_image' => 'products/15.jpg',
                'active' => true
            ],
            [
                'product_name' => 'Snail White Lotion',
                'price' => 15.00,
                'description' => 'Contains snail mucin for hydration and brightening',
                'stock_quantity' => 40,
                'category' => 'Body Care',
                'product_image' => 'products/16.jpg',
                'active' => true
            ],
            [
                'product_name' => 'Madu Tualang Asli',
                'price' =>  38.00,
                'description' => 'Wild forest honey known for its antibacterial and healing power',
                'stock_quantity' => 40,
                'category' => 'Immune Booster',
                'product_image' => 'products/17.jpg',
                'active' => true
            ],
            [
                'product_name' => 'Halia Bara Capsule',
                'price' => 32.00,
                'description' => 'Improves digestion and supports natural body warmth',
                'stock_quantity' => 50,
                'category' => 'Immune Booster',
                'product_image' => 'products/18.jpg',
                'active' => true
            ],
            [
                'product_name' => 'Cuka Kurma',
                'price' => 28.00,
                'description' => 'Date vinegar rich in enzymes and antioxidants',
                'stock_quantity' => 45,
                'category' => 'Immune Booster',
                'product_image' => 'products/19.jpg',
                'active' => true
            ],
            [
                'product_name' => 'Buah Zaitun Kering',
                'price' => 26.00,
                'description' => 'Dried olives packed with antioxidants to support immunity',
                'stock_quantity' => 35,
                'category' => 'Immune Booster',
                'product_image' => 'products/20.jpg',
                'active' => true
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}