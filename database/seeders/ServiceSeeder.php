<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services = [
            [
                'service_name' => 'Haircut - Women',
                'description' => 'Professional haircut for women including wash and style.',
                'duration_minutes' => 60,
                'price' => 45.00,
                'active' => true
            ],
            [
                'service_name' => 'Haircut - Men',
                'description' => 'Professional haircut for men including wash and style.',
                'duration_minutes' => 45,
                'price' => 35.00,
                'active' => true
            ],
            [
                'service_name' => 'Hair Coloring',
                'description' => 'Full hair coloring service with premium products.',
                'duration_minutes' => 120,
                'price' => 85.00,
                'active' => true
            ],
            [
                'service_name' => 'Highlights',
                'description' => 'Partial or full highlights to enhance your natural color.',
                'duration_minutes' => 90,
                'price' => 75.00,
                'active' => true
            ],
            [
                'service_name' => 'Balayage',
                'description' => 'Hand-painted highlights for a natural, sun-kissed look.',
                'duration_minutes' => 150,
                'price' => 120.00,
                'active' => true
            ],
            [
                'service_name' => 'Blowout',
                'description' => 'Professional blow dry and style.',
                'duration_minutes' => 45,
                'price' => 35.00,
                'active' => true
            ],
            [
                'service_name' => 'Deep Conditioning Treatment',
                'description' => 'Intensive conditioning to restore moisture and shine.',
                'duration_minutes' => 30,
                'price' => 25.00,
                'active' => true
            ],
            [
                'service_name' => 'Basic Manicure',
                'description' => 'Nail shaping, cuticle care, and polish application.',
                'duration_minutes' => 30,
                'price' => 25.00,
                'active' => true
            ],
            [
                'service_name' => 'Gel Manicure',
                'description' => 'Long-lasting gel polish that stays chip-free for weeks.',
                'duration_minutes' => 45,
                'price' => 35.00,
                'active' => true
            ],
            [
                'service_name' => 'Basic Pedicure',
                'description' => 'Foot soak, exfoliation, nail care, and polish.',
                'duration_minutes' => 45,
                'price' => 35.00,
                'active' => true
            ],
            [
                'service_name' => 'Spa Pedicure',
                'description' => 'Deluxe pedicure with extended massage and paraffin treatment.',
                'duration_minutes' => 60,
                'price' => 50.00,
                'active' => true
            ],
            [
                'service_name' => 'Basic Facial',
                'description' => 'Cleansing, exfoliation, mask, and moisturizer.',
                'duration_minutes' => 60,
                'price' => 60.00,
                'active' => true
            ],
            [
                'service_name' => 'Anti-Aging Facial',
                'description' => 'Specialized treatment to reduce fine lines and improve skin texture.',
                'duration_minutes' => 75,
                'price' => 85.00,
                'active' => true
            ],
            [
                'service_name' => 'Acne Treatment Facial',
                'description' => 'Deep cleansing facial designed for acne-prone skin.',
                'duration_minutes' => 60,
                'price' => 70.00,
                'active' => true
            ],
            [
                'service_name' => 'Swedish Massage',
                'description' => 'Relaxing full-body massage to release tension.',
                'duration_minutes' => 60,
                'price' => 70.00,
                'active' => true
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}