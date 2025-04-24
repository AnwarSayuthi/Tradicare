<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id('service_id');
            $table->string('service_name');
            $table->text('description');
            $table->integer('duration_minutes');
            $table->decimal('price', 10, 2);
            $table->string('icon')->nullable();
            $table->string('category')->nullable();
            $table->boolean('active')->default(true);
        });
        
        // Insert traditional healing services
        DB::table('services')->insert([
            // Traditional Healing Treatments
            [
                'service_name' => 'Joint Pain Relief Therapy',
                'description' => 'Traditional therapy targeting joint inflammation and pain using ancient techniques and herbal compresses. Ideal for arthritis, sports injuries, and chronic joint conditions.',
                'duration_minutes' => 60,
                'price' => 150.00,
                'icon' => 'bi-activity',
                'category' => 'traditional',
                'active' => true
            ],
            [
                'service_name' => 'Bone Fracture Recovery Treatment',
                'description' => 'Specialized traditional therapy to accelerate bone healing and reduce pain from fractures or breaks. Combines gentle manipulation with herbal applications to promote recovery.',
                'duration_minutes' => 75,
                'price' => 180.00,
                'icon' => 'bi-bandaid',
                'category' => 'traditional',
                'active' => true
            ],
            [
                'service_name' => 'Vein & Circulation Therapy',
                'description' => 'Traditional treatment to improve blood circulation, reduce swelling, and alleviate vein-related issues. Particularly effective for varicose veins and poor circulation.',
                'duration_minutes' => 60,
                'price' => 140.00,
                'icon' => 'bi-heart-pulse',
                'category' => 'traditional',
                'active' => true
            ],
            [
                'service_name' => 'Full Body Ache Relief',
                'description' => 'Comprehensive traditional massage targeting multiple pain points throughout the body. Perfect for overall tension release and chronic pain management.',
                'duration_minutes' => 90,
                'price' => 200.00,
                'icon' => 'bi-person-fill',
                'category' => 'traditional',
                'active' => true
            ],
            [
                'service_name' => 'Back & Spine Alignment',
                'description' => 'Traditional therapy focusing on back pain relief and spinal alignment using pressure point techniques and gentle manipulation.',
                'duration_minutes' => 60,
                'price' => 160.00,
                'icon' => 'bi-arrow-up-square',
                'category' => 'traditional',
                'active' => true
            ],
            [
                'service_name' => 'Nerve Pain Treatment',
                'description' => 'Specialized therapy targeting nerve-related pain and discomfort using traditional methods to reduce inflammation and promote healing.',
                'duration_minutes' => 60,
                'price' => 150.00,
                'icon' => 'bi-lightning',
                'category' => 'traditional',
                'active' => true
            ],
            
            // Massage Therapies
            [
                'service_name' => 'Traditional Malay Urut',
                'description' => 'Ancient Malay massage technique using long kneading strokes and thumb pressure to release tension and improve energy flow throughout the body.',
                'duration_minutes' => 60,
                'price' => 120.00,
                'icon' => 'bi-hand-thumbs-up',
                'category' => 'massage',
                'active' => true
            ],
            [
                'service_name' => 'Herbal Compress Massage',
                'description' => 'Therapeutic massage using heated herbal compresses to relieve muscle tension, improve circulation and reduce inflammation.',
                'duration_minutes' => 75,
                'price' => 160.00,
                'icon' => 'bi-flower1',
                'category' => 'massage',
                'active' => true
            ],
            [
                'service_name' => 'Hot Stone Therapy',
                'description' => 'Smooth, heated stones are placed on specific points on the body to warm and loosen tight muscles and balance energy centers in the body.',
                'duration_minutes' => 90,
                'price' => 180.00,
                'icon' => 'bi-circle-fill',
                'category' => 'massage',
                'active' => true
            ],
            
            // Facial & Body Treatments
            [
                'service_name' => 'Anti-Aging Facial',
                'description' => 'A rejuvenating facial treatment designed to reduce fine lines and wrinkles, improve skin elasticity, and promote a youthful appearance.',
                'duration_minutes' => 60,
                'price' => 140.00,
                'icon' => 'bi-emoji-smile',
                'category' => 'facial',
                'active' => true
            ],
            [
                'service_name' => 'Body Scrub & Polish',
                'description' => 'An exfoliating treatment that removes dead skin cells, improves circulation, and leaves your skin smooth and glowing.',
                'duration_minutes' => 45,
                'price' => 100.00,
                'icon' => 'bi-stars',
                'category' => 'body',
                'active' => true
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
