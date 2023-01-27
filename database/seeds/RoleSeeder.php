<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if (empty(App\Models\Role::where('roleName', 'Customer')->get()->first())) {
            DB::table('roles')->updateOrInsert([
                'roleName' => 'Customer',
                'permissions' => '[]',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]);
        }

    }
}
