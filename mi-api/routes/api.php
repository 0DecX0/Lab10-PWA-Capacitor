<?php

use App\Models\User;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

// Login CON límite de intentos y registro de logs (Control Preventivo y Detectivo)
Route::post('/login', function (Request $request) {
    $user = User::where('email', $request->email)->first();
    if (! $user || ! Hash::check($request->password, $user->password)) {
        Log::warning('Login fallido', ['email' => $request->email, 'ip' => $request->ip()]); // <-- NUEVO
        return response()->json(['message' => 'Credenciales inválidas'], 401);
    }
    return ['token' => $user->createToken('app')->plainTextToken];
})->middleware('throttle:5,1'); // <-- NUEVO: 5 intentos por minuto

Route::middleware('auth:sanctum')->group(function () {
    // Correcta: solo los pedidos del usuario autenticado
    Route::get('/pedidos', fn (Request $r) => $r->user()->pedidos);
    
    // CORREGIDO (IDOR): Solo busca entre los pedidos del usuario, si no lo encuentra da 404
    Route::get('/pedidos/{id}', fn (Request $r, $id) => $r->user()->pedidos()->findOrFail($id));

    // CORREGIDO (Validación): Rechaza datos incompletos o fuera de rango (422)
    Route::post('/pedidos', function (Request $r) {
        $datos = $r->validate([
            'descripcion' => 'required|string|max:255',
            'total' => 'required|numeric|min:0',
        ]);
        return $r->user()->pedidos()->create($datos);
    });

    // NUEVO: Ruta de logout para revocar el token en el servidor (Control Correctivo)
    Route::post('/logout', function (Request $r) {
        $r->user()->currentAccessToken()->delete();
        return response()->noContent();
    });
});