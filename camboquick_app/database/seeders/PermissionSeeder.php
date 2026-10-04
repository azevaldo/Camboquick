<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Permission::create(['name' => 'gerir vendas']);
        Permission::create(['name' => 'gerir compras']);
        Permission::create(['name' => 'gerir movimentos']);
        Permission::create(['name' => 'gerir usuarios']);
        Permission::create(['name' => 'gerir estatisticas']);
        Permission::create(['name' => 'gerir tudo']);
         
        


         // Criar papéis e atribuir permissões
         $admin = Role::create(['name' => 'admin']);
         $admin->givePermissionTo(['gerir vendas','gerir compras','gerir movimentos','gerir usuarios','gerir estatisticas'
        ,'gerir tudo'
        ]);
         $gerente = Role::create(['name' => 'gerente']);
         $gerente->givePermissionTo(['gerir vendas','gerir compras','gerir movimentos','gerir estatisticas']);
 
         $caixa = Role::create(['name' => 'caixa']);
         $caixa->givePermissionTo(['gerir vendas']); 
         $arquivado=Role::create(['name'=>'arquivado']);
    }
}
