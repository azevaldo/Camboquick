<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\Telefone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        
       $empresa= Empresa::create([
            'nome'=>'Lua cheia',
            'email'=>'camboquick@gmail.com',
            'nif'=>'Indefinido',
            'local'=>'Catumbela/Gama',
        ]);
        Telefone::create([
            'numero'=>'938531896',
            'empresa_id'=>$empresa->id,
        ]);
    }
}
