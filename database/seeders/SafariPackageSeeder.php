<?php

namespace Database\Seeders;

use App\Models\SafariPackage;
use App\Models\SafariCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SafariPackageSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch categories to link them
        $categories = SafariCategory::all()->pluck('id', 'slug');

        $packages = [
            [
                'name' => '3-Day Gorilla Trekking Bwindi',
                'category_slug' => 'primate-trekking',
                'summary' => 'A life-changing encounter with the gentle giants of Bwindi Impenetrable Forest.',
                'description' => '<h1>Experience the Magic</h1><p>This 3-day safari takes you to the heart of Bwindi, home to half of the world\'s mountain gorilla population. Includes permits, transport from Kampala, and eco-lodge accommodation.</p>',
                'price' => 1450.00,
                'duration_days' => 3,
                'location' => 'Bwindi, Uganda',
                'difficulty' => 'challenging',
                'is_featured' => true,
            ],
            [
                'name' => '5-Day Murchison Falls & Rhinos',
                'category_slug' => 'classic-savannah-safaris',
                'summary' => 'Boat cruises on the Nile and the Big Five at Uganda\'s largest national park.',
                'description' => '<h1>Wildlife & The Nile</h1><p>Visit the Ziwa Rhino Sanctuary followed by a spectacular safari in Murchison Falls. See the world\'s most powerful waterfall and hunt for lions, leopards, and elephants.</p>',
                'price' => 850.00,
                'duration_days' => 5,
                'location' => 'Murchison Falls, Uganda',
                'difficulty' => 'moderate',
                'is_featured' => true,
            ],
            [
                'name' => '10-Day Best of East Africa',
                'category_slug' => 'classic-savannah-safaris', // Or great-migration
                'summary' => 'The ultimate circuit: Serengeti, Maasai Mara, and Ngorongoro Crater.',
                'description' => '<h1>The Great Circuit</h1><p>Cross borders to witness the great wildebeest migration. Luxury camping and high-end game drives through the most iconic parks in Kenya and Tanzania.</p>',
                'price' => 4200.00,
                'duration_days' => 10,
                'location' => 'Tanzania & Kenya',
                'difficulty' => 'moderate',
                'is_featured' => false,
            ],
            [
                'name' => '2-Day Jinja Adventure Escape',
                'category_slug' => 'mountain-hiking-climbing', // Using hiking/adventure cat
                'summary' => 'White water rafting and source of the Nile exploration.',
                'description' => '<h1>Adrenaline in Jinja</h1><p>Perfect weekend getaway from Kampala. Grade 5 rafting, bungee jumping, and sunset cruises on the River Nile.</p>',
                'price' => 250.00,
                'duration_days' => 2,
                'location' => 'Jinja, Uganda',
                'difficulty' => 'moderate',
                'is_featured' => false,
            ],
            [
                'name' => '7-Day Rwenzori Mountains Trek',
                'category_slug' => 'mountain-hiking-climbing',
                'summary' => 'Conquer the "Mountains of the Moon" in this challenging alpine trek.',
                'description' => '<h1>High Altitude Adventure</h1><p>A specialized trek through unique bog vegetation and glacial landscapes to Margherita Peak.</p>',
                'price' => 1200.00,
                'duration_days' => 7,
                'location' => 'Kasese, Uganda',
                'difficulty' => 'challenging',
                'is_featured' => false,
            ],
        ];

        foreach ($packages as $pkg) {
            SafariPackage::create([
                'name' => $pkg['name'],
                'slug' => Str::slug($pkg['name']),
                'summary' => $pkg['summary'],
                'description' => $pkg['description'],
                'price' => $pkg['price'],
                'duration_days' => $pkg['duration_days'],
                'location' => $pkg['location'],
                'difficulty' => $pkg['difficulty'],
                'is_featured' => $pkg['is_featured'],
                'status' => 'published',
                'safari_category_id' => $categories[$pkg['category_slug']] ?? null,
                'max_people' => 12,
            ]);
        }
    }
}