<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('users')->updateOrInsert(['email'=> 'admin@admin.com'],[
            'name' => 'admin',
            'username' => 'admin',
            'userType' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin123'),
            // 'password'=>Hash::make('admin123'),
            'alphaRole' => 'SUPER',
            'email_verified_at' => now(),
        ]);
    }
}
