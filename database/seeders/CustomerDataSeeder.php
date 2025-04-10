<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Service;
use Carbon\Carbon;
use Faker\Factory as Faker;

class CustomerDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $customers = User::where('role', 'customer')->get();
        $products = Product::all();
        $services = Service::all();
        
        // Create data for each customer
        foreach ($customers as $customer) {
            // Create 1-3 carts per customer
            for ($i = 0; $i < $faker->numberBetween(1, 3); $i++) {
                $cart = Cart::create([
                    'user_id' => $customer->id,
                    'status' => $faker->randomElement(['completed', 'active'])
                ]);
                
                // Add 1-5 items to each cart
                $cartTotal = 0;
                for ($j = 0; $j < $faker->numberBetween(1, 5); $j++) {
                    $product = $products->random();
                    $quantity = $faker->numberBetween(1, 3);
                    $unitPrice = $product->price;
                    $subtotal = $quantity * $unitPrice;
                    $cartTotal += $subtotal;
                    
                    CartItem::create([
                        'cart_id' => $cart->cart_id,
                        'product_id' => $product->product_id,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice
                    ]);
                }
                
                // Create order for completed carts
                if ($cart->status === 'completed') {
                    $orderDate = $faker->dateTimeBetween('-6 months', 'now');
                    $order = Order::create([
                        'user_id' => $customer->id,
                        'cart_id' => $cart->cart_id,
                        'order_date' => $orderDate,
                        'total_amount' => $cartTotal,
                        'payment_status' => $faker->randomElement(['pending', 'paid']),
                        'shipping_address' => $faker->address,
                        'status' => $faker->randomElement(['processing', 'shipped', 'delivered'])
                    ]);
                    
                    // Create order items
                    foreach ($cart->cartItems as $cartItem) {
                        OrderItem::create([
                            'order_id' => $order->order_id,
                            'product_id' => $cartItem->product_id,
                            'quantity' => $cartItem->quantity,
                            'unit_price' => $cartItem->unit_price,
                            'subtotal' => $cartItem->quantity * $cartItem->unit_price
                        ]);
                    }
                    
                    // Create payment for paid orders
                    if ($order->payment_status === 'paid') {
                        Payment::create([
                            'user_id' => $customer->id,
                            'order_id' => $order->order_id,
                            'appointment_id' => null,
                            'amount' => $order->total_amount,
                            'payment_date' => Carbon::parse($orderDate)->addDays($faker->numberBetween(0, 2)),
                            'payment_method' => $faker->randomElement(['credit_card', 'paypal', 'bank_transfer']),
                            'status' => 'completed',
                            'transaction_id' => 'TXN' . $faker->unique()->randomNumber(8)
                        ]);
                    }
                }
            }
            
            // Create 1-5 appointments per customer
            for ($i = 0; $i < $faker->numberBetween(1, 5); $i++) {
                $service = $services->random();
                $appointmentDate = $faker->dateTimeBetween('-3 months', '+1 month');
                $endTime = (clone $appointmentDate)->modify('+' . $service->duration_minutes . ' minutes');
                
                $appointment = Appointment::create([
                    'user_id' => $customer->id,
                    'service_id' => $service->service_id,
                    'appointment_date' => $appointmentDate,
                    'end_time' => $endTime,
                    'status' => $appointmentDate < new \DateTime() ? 
                        $faker->randomElement(['completed', 'cancelled']) : 
                        'scheduled',
                    'notes' => $faker->optional(0.7)->sentence
                ]);
                
                // Create payment for completed appointments
                if ($appointment->status === 'completed') {
                    Payment::create([
                        'user_id' => $customer->id,
                        'order_id' => null,
                        'appointment_id' => $appointment->appointment_id,
                        'amount' => $service->price,
                        'payment_date' => Carbon::parse($appointmentDate),
                        'payment_method' => $faker->randomElement(['credit_card', 'cash', 'paypal']),
                        'status' => 'completed',
                        'transaction_id' => 'TXN' . $faker->unique()->randomNumber(8)
                    ]);
                }
            }
        }
    }
}