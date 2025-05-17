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
                'service_name' => 'Tradicare Signature Massage',
                'description' => 'Full-body traditional Malay massage to relieve muscle stiffness and fatigue',
                'duration_minutes' => 60,
                'price' => 80.00,
                'active' => true
            ],
            [
                'service_name' => 'Herbal Clay Massage',
                'description' => 'A unique mixture of hand massage and heated clay compress. Herbal plants promote deep relaxation while soothing aching muscles and easing fatigue',
                'duration_minutes' => 60,
                'price' => 95.00,
                'active' => true
            ],
            [
                'service_name' => 'Rawatan Patah & Seliuh',
                'description' => 'Bone setting and joint adjustment for fractures, sprains, or dislocations',
                'duration_minutes' => 60,
                'price' => 100.00,
                'active' => true
            ],
            [
                'service_name' => 'Rawatan Batuk & Asma Herba',
                'description' => 'Herbal steam and chest massage to relieve cough, asthma, and sinus congestion using traditional remedies',
                'duration_minutes' => 30,
                'price' => 55.00,
                'active' => true
            ],
            [
                'service_name' => 'Rawatan Saraf',
                'description' => 'Traditional nerve treatment for numbness, pinched nerves, or muscle weakness',
                'duration_minutes' => 60,
                'price' => 90.00,
                'active' => true
            ],
            [
                'service_name' => 'Rawatan Bekam (Lelaki)',
                'description' => 'Cupping therapy to remove toxins and improve circulation (men only)',
                'duration_minutes' => 30,
                'price' => 60.00,
                'active' => true
            ],
            [
                'service_name' => 'Rawatan Bekam (Wanita)',
                'description' => 'Female-friendly cupping therapy session with a female therapist',
                'duration_minutes' => 30,
                'price' => 60.00,
                'active' => true
            ],
            [
                'service_name' => 'Urutan Ibu Mengandung',
                'description' => 'Gentle prenatal massage to reduce swelling, stress, and back pain',
                'duration_minutes' => 60,
                'price' => 85.00,
                'active' => true
            ],
            [
                'service_name' => 'Urutan Selepas Bersalin',
                'description' => 'Postnatal massage to help recovery, firm the uterus, and improve circulation',
                'duration_minutes' => 60,
                'price' => 90.00,
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