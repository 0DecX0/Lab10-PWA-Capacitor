<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/productos', function () {
    return response()->json([
        'data' => [
            ['id' => 1, 'nombre' => 'Laptop Lenovo', 'precio' => 2500],
            ['id' => 2, 'nombre' => 'Mouse Logitech', 'precio' => 50],
            ['id' => 3, 'nombre' => 'Monitor Samsung', 'precio' => 800],
            ['id' => 4, 'nombre' => 'Teclado Mecánico', 'precio' => 150],
        ]
    ]);
});