<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = ['Concert', 'Conférence', 'Atelier', 'Festival'];

        for ($i = 1; $i <= 50; $i++) {
            DB::table('events')->insert([
                'organizer_id' => [14, 15, 16][array_rand([14, 15, 16])],
                'title' => 'Événement ' . $i,
                'description' => Str::random(100),
                'location' => 'Lieu ' . rand(1, 20),
                'event_date' => now()->addDays(rand(1, 365)),
                'banner_url' => 'https://example.com/banners/banner' . $i . '.jpg',
                'is_active' => true,
                'created_at' => now(),
            ]);
        }
    }
}
