<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// TEMPORARY DEBUG ROUTE — remove after diagnosing
Route::get('/debug-check', function () {
    $publicPath = public_path();
    $htaccessPath = public_path('.htaccess');

    return response()->json([
        'public_path' => $publicPath,
        'htaccess_exists' => file_exists($htaccessPath),
        'htaccess_content' => file_exists($htaccessPath) ? file_get_contents($htaccessPath) : null,
        'public_files' => scandir($publicPath),
        'apache_mod_rewrite_loaded' => function_exists('apache_get_modules')
            ? in_array('mod_rewrite', apache_get_modules())
            : 'apache_get_modules() not available (mod_php not used, or function disabled)',
    ]);
});