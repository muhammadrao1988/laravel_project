<?php

use Illuminate\Database\Seeder;
class FlagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data[] = [ "flagType" => "mobile_app_versions", "name" => "Android App", "field1" => "androidAppVersion", "field2" => "1.0.0"];
        $data[] = [ "flagType" => "mobile_app_versions", "name" => "IOS App", "field1" => "iosAppVersion", "field2" => "1.0.0"];

        foreach ($data as $flag){
            if(empty(App\Models\Flag::where('flagType', $flag['flagType'])->where('name', $flag['name'])->get()->first())){
                DB::table('flags')->updateOrInsert([
                    'flagType' => $flag['flagType'],
                    'name' => $flag['name'],
                    'field1' => !empty($flag['field1']) ? $flag['field1'] : null,
                    'field2' => !empty($flag['field2']) ? $flag['field2'] : null
                ]);
            }
        }
    }
}
