<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
        public function run(): void
    {
        $ana = \App\Models\User::factory()->create([
            'email' => 'ana@test.pe',
            'password' => \Illuminate\Support\Facades\Hash::make('secreto123')
        ]);
        
        $beto = \App\Models\User::factory()->create([
            'email' => 'beto@test.pe',
            'password' => \Illuminate\Support\Facades\Hash::make('secreto123')
        ]);

        \App\Models\Pedido::create([
            'user_id' => $ana->id,
            'descripcion' => 'Pedido de Ana',
            'total' => 50
        ]);

        \App\Models\Pedido::create([
            'user_id' => $beto->id,
            'descripcion' => 'Pedido de Beto',
            'total' => 80
        ]);
    }
}
