<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/about', function () {
    return view('about');
});

Route::get('/blog', function () {
    return view('blog');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/hotel', function () {
    return view('hotel');
});

Route::get('/tour', function () {
    return view('tours.index');
});

Route::get('/tour-details', function () {
    return view('tours.show');
});

Route::get('/packages', function () {
    return view('packages.index');
});

Route::get('/packages', function () {
    return view('packages.show');
});

Route::get('/hotel-single', function () {
    return view('hotel-single');
});

Route::get('/blog-single', function () {
    return view('blog-single');
});