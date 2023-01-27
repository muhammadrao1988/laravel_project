<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ConfigurationSeeder::class);
        $this->call(FlagSeeder::class);
        $this->call(FlagTypeSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(CronJobSeeder::class);
        $this->call(TempSeeder::class);
        $this->call(CategoriesSeeder::class);

    }
}
