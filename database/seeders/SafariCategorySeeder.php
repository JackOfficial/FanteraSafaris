<?php

namespace Database\Seeders;

use App\Models\SafariCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SafariCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Primate Trekking',
                'description' => 'Unforgettable encounters with Mountain Gorillas and Chimpanzees in the rainforests of Uganda and Rwanda.'
            ],
            [
                'name' => 'Classic Savannah Safaris',
                'description' => 'Experience the vast plains of East Africa, from Murchison Falls to the Serengeti, in search of the Big Five.'
            ],
            [
                'name' => 'Mountain Hiking & Climbing',
                'description' => 'Summit the snow-capped Rwenzori Mountains or the iconic Mt. Kilimanjaro.'
            ],
            [
                'name' => 'Family & Group Tours',
                'description' => 'Curated travel experiences designed for safety, education, and fun for all ages.'
            ],
            [
                'name' => 'Birding & Nature Walks',
                'description' => 'Specialized tours for bird lovers to spot rare species like the Shoebill Stork.'
            ],
            [
                'name' => 'Cultural & Community Tours',
                'description' => 'Go beyond the wildlife and connect with the heart and soul of East African people.'
            ]
        ];

        foreach ($categories as $category) {
            SafariCategory::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                ]
            );
        }
    }
}