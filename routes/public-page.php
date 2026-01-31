<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('public.index'); // <- note the dot notation for subfolders
})->name('home');

Route::get('/about-us', function () {
    return view('public.about-us');
});

Route::get('/fees-structure', function () {
    return view('public.fees-structure');
});

Route::get('/courses', function () {
    return view('public.courses');
});


Route::get('/teachers', function () {
    return view('public.teachers');
});


Route::get('/contact-us', function () {
    return view('public.contact-us');
});


Route::get('/blog', function () {
    return view('public.blog');
});


Route::get('/hello', function () {
    return view('public.hello');
});