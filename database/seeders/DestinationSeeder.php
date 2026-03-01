<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DestinationSeeder extends Seeder
{
    public function run(): void
    {
        $destinations = [
            // UGANDA (Core Business)
            [
                'name' => 'Bwindi Impenetrable National Park',
                'country' => 'Uganda',
                'description' => 'Home to half of the world\'s remaining mountain gorillas, this ancient rainforest offers the ultimate primate trekking experience.',
                'is_featured' => true,
            ],
            [
                'name' => 'Murchison Falls National Park',
                'country' => 'Uganda',
                'description' => 'Where the Nile forces its way through a narrow gap, creating the world\'s most powerful waterfall alongside incredible savannah wildlife.',
                'is_featured' => true,
            ],
            [
                'name' => 'Queen Elizabeth National Park',
                'country' => 'Uganda',
                'description' => 'Famous for tree-climbing lions and the Kazinga Channel boat cruise, it’s a medley of craters, tropical forests, and savannah.',
                'is_featured' => false,
            ],

            // KENYA
            [
                'name' => 'Maasai Mara National Reserve',
                'country' => 'Kenya',
                'description' => 'Global stage for the Great Migration. An essential destination for Big Five sightings and vast golden plains.',
                'is_featured' => true,
            ],
            [
                'name' => 'Amboseli National Park',
                'country' => 'Kenya',
                'description' => 'Known for its large elephant herds and views of immense Mount Kilimanjaro across the border.',
                'is_featured' => false,
            ],

            // TANZANIA
            [
                'name' => 'Serengeti National Park',
                'country' => 'Tanzania',
                'description' => 'A UNESCO World Heritage site and home to the circular Great Migration, offering some of the best predator sightings in Africa.',
                'is_featured' => true,
            ],
            [
                'name' => 'Ngorongoro Conservation Area',
                'country' => 'Tanzania',
                'description' => 'The world\'s largest inactive volcanic caldera, forming a natural enclosure for a dense population of wildlife.',
                'is_featured' => false,
            ],

            // RWANDA
            [
                'name' => 'Volcanoes National Park',
                'country' => 'Rwanda',
                'description' => 'A stunning chain of dormant volcanoes and the setting for Dian Fossey’s gorilla conservation work.',
                'is_featured' => false,
            ],
            [
                'name' => 'Akagera National Park',
                'country' => 'Rwanda',
                'description' => 'Central Africa\'s largest protected wetland and the only refuge for savannah-adapted animals in Rwanda.',
                'is_featured' => false,
            ],
        ];

        foreach ($destinations as $dest) {
            Destination::create([
                'name' => $dest['name'],
                'slug' => Str::slug($dest['name']),
                'country' => $dest['country'],
                'description' => $dest['description'],
                'is_featured' => $dest['is_featured'],
                'image' => null, // You will upload high-res photos via the UI later
            ]);
        }
    }
}