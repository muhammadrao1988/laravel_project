<?php

use Illuminate\Database\Seeder;

class FlagTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data[] = [ "name" => "Mobile App Versions"];

        foreach ($data as $flagtype) {
            if(empty(App\Models\FlagType::where('name', $flagtype['name'])->where('code', strtolower(preg_replace('/\s+/', '_', $flagtype['name'])))->get()->first())){
                DB::table('flag_types')->updateOrInsert([
                    'name' => $flagtype['name'],
                    'code' => strtolower(preg_replace('/\s+/', '_', $flagtype['name'])),
                ]);
            }
        }
    }
}
