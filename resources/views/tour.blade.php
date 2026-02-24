@extends('layouts.app')

@section('title', 'Premier East African Safari Destinations | Fantera Safaris')
@section('meta_description', 'Discover the best destinations in Uganda, Kenya, and Tanzania. From the misty mountains of Bwindi to the vast Serengeti plains.')
@section('meta_keywords', 'Uganda Destinations, Serengeti Tours, Masai Mara Safaris, Gorilla Trekking Locations')
@section('og_image', asset('front/images/zanzibar_beach.jpg'))

@section('content')
    <div class="hero-wrap relative h-[80vh] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('front/images/zanzibar_beach.jpg') }}" class="w-full h-full object-cover scale-105 motion-safe:animate-slow-zoom" alt="Hero">
            <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-transparent to-stone-50"></div>
        </div>
        
        <div class="container relative z-10 text-center text-white mt-20">
            <nav class="flex justify-center space-x-2 mb-6 opacity-80 uppercase tracking-widest text-xs">
                <a href="/" class="hover:text-emerald-400">Home</a>
                <span>/</span>
                <span class="text-emerald-400">Tours</span>
            </nav>
            <h1 class="text-5xl md:text-7xl font-serif font-light tracking-tight mb-4">Explore East Africa</h1>
            <p class="text-lg opacity-90 max-w-xl mx-auto font-light">Hand-picked safaris designed for the conscious traveler.</p>
        </div>
    </div>

    <section class="pb-24 bg-stone-50">
        <div class="container mx-auto px-4">
            
            <div class="flex flex-col lg:flex-row gap-12 -mt-16 relative z-20">
                
                <aside class="lg:w-1/4">
                    <div class="sticky top-24 p-8 bg-white/80 backdrop-blur-2xl rounded-[2rem] shadow-2xl shadow-stone-200/50 border border-white">
                        <h3 class="text-xl font-bold text-stone-900 mb-6 flex items-center">
                            <i class="icon-filter mr-2 text-emerald-600"></i> Refine Search
                        </h3>
                        
                        <form action="#" class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-[10px] uppercase tracking-wider font-bold text-stone-400 ml-1">Destination</label>
                                <div class="relative">
                                    <select class="w-full bg-stone-100 border-none rounded-2xl py-4 px-5 appearance-none focus:ring-2 focus:ring-emerald-500/20 transition-all">
                                        <option value="">All Regions</option>
                                        <option>Uganda (Gorillas)</option>
                                        <option>Kenya (Maasai Mara)</option>
                                        <option>Tanzania (Serengeti)</option>
                                    </select>
                                    <i class="ion-ios-arrow-down absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none opacity-50"></i>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex justify-between items-end">
                                    <label class="text-[10px] uppercase tracking-wider font-bold text-stone-400 ml-1">Budget</label>
                                    <span class="text-emerald-700 font-serif font-bold">$500 - $10k</span>
                                </div>
                                <input type="range" class="w-full accent-emerald-600 h-1 bg-stone-200 rounded-lg appearance-none cursor-pointer" min="500" max="10000">
                            </div>

                            <button type="submit" class="w-full bg-stone-900 text-white py-4 rounded-2xl font-bold hover:bg-emerald-800 hover:shadow-lg hover:shadow-emerald-900/20 transition-all duration-300">
                                Apply Filters
                            </button>
                        </form>
                    </div>
                </aside>

                <div class="lg:w-3/4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        @php
                            $tours = [
                                ['name' => 'Bwindi Gorilla Trek', 'price' => '1,800', 'loc' => 'Uganda', 'img' => 'Bwindi.jpg', 'desc' => 'Encounter the mountain gorillas.'],
                                ['name' => 'Serengeti Migration', 'price' => '1,250', 'loc' => 'Tanzania', 'img' => 'Serengeti.jpg', 'desc' => 'Witness the Great Migration.'],
                                ['name' => 'Maasai Mara Luxury', 'price' => '980', 'loc' => 'Kenya', 'img' => 'Maasai mara.jpg', 'desc' => 'The ultimate Big Five experience.'],
                            ];
                        @endphp

                        @foreach($tours as $tour)
                        <div class="group bg-white rounded-[2.5rem] overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-stone-100">
                            <div class="relative h-72 overflow-hidden">
                                <img src="{{ asset('front/images/' . $tour['img']) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                <div class="absolute top-6 left-6">
                                    <span class="bg-white/90 backdrop-blur px-4 py-2 rounded-full text-[10px] font-bold uppercase tracking-widest shadow-sm">
                                        {{ $tour['loc'] }}
                                    </span>
                                </div>
                                <div class="absolute bottom-6 right-6">
                                    <div class="bg-emerald-600 text-white p-4 rounded-2xl shadow-xl">
                                        <p class="text-[10px] opacity-80 leading-none">From</p>
                                        <p class="text-xl font-serif font-bold leading-tight">${{ $tour['price'] }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-8">
                                <div class="flex items-center space-x-1 mb-3 text-amber-400 text-xs">
                                    @for($i=0; $i<5; $i++) <i class="icon-star"></i> @endfor
                                </div>
                                <h3 class="text-2xl font-serif font-medium text-stone-900 mb-2 group-hover:text-emerald-700 transition-colors">
                                    {{ $tour['name'] }}
                                </h3>
                                <p class="text-stone-500 text-sm font-light mb-6 leading-relaxed">{{ $tour['desc'] }}</p>
                                
                                <div class="flex items-center justify-between pt-6 border-t border-stone-50">
                                    <span class="text-xs font-bold uppercase tracking-tighter text-stone-400 flex items-center">
                                        <i class="icon-calendar-o mr-2"></i> 5 - 12 Days
                                    </span>
                                    <a href="#" class="text-stone-900 font-bold text-sm flex items-center group/btn">
                                        View Details 
                                        <i class="icon-arrow-right ml-2 transition-transform group-hover/btn:translate-x-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach

                    </div>

                    <div class="mt-16 flex justify-center">
                        <nav class="inline-flex p-2 bg-white rounded-2xl shadow-sm border border-stone-100 space-x-1">
                            <a href="#" class="px-4 py-2 rounded-xl hover:bg-stone-50 transition-colors">Prev</a>
                            <a href="#" class="px-4 py-2 bg-emerald-600 text-white rounded-xl shadow-md">1</a>
                            <a href="#" class="px-4 py-2 rounded-xl hover:bg-stone-50 transition-colors">2</a>
                            <a href="#" class="px-4 py-2 rounded-xl hover:bg-stone-50 transition-colors">Next</a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection