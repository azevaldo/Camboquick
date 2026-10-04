<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $admin=User::create([
            'name'=>'Admin',
            'email'=>'admin@gmail.com',
            'sexo'=>'Masculino',
            'password'=>bcrypt('password'),
            'slug'=>'Admin',
        ]);
        Role::firstOrCreate(['name'=>'admin']);
        $admin->syncRoles('admin');


        $gerente=User::create([
            'name'=>'Gerente',
            'email'=>'gerente@gmail.com',
            'sexo'=>'Masculino',
            'password'=>bcrypt('password'),
            'slug'=>'Gerente',
        ]);
        Role::firstOrCreate(['name'=>'gerente']);
        $gerente->syncRoles('gerente');
        $caixa=User::create([
            'name'=>'Caixa',
            'email'=>'caixa@gmail.com',
            'sexo'=>'Masculino',
            'password'=>bcrypt('password'),
            'slug'=>'Caixa',
        ]);
        Role::firstOrCreate(['name'=>'caixa']);
        $caixa->syncRoles('caixa');
    }
}
