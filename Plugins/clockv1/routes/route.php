<?php
use Illuminate\Support\Facades\Route;

$info = json_decode(file_get_contents(__DIR__ . "/../info.json"), true);
Route::get('/Plugins/'.$info['app'].'v'.$info['version'].'/index', function () use ($info) {
    $file = base_path('Plugins/'.$info['app'].'v'.$info['version']).'/index.php';
    ob_start();
    include $file;
    return ob_get_clean();
});