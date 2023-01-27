<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $entries = [
            '0'=>[
                'title'=> 'For her',
                'imageName'=>'giftidea-banner.png',
                'imageName'=>'giftidea-banner.png',
            ],
            '1'=>[
                'title'=> 'For him',
                'imageName'=>'giftidea-banner.png'
            ],
            '2'=>[
                'title'=> 'For techies',
                'imageName'=>'giftidea-banner.png'
            ],
            '3'=>[
                'title'=> 'For children',
                'imageName'=>'giftidea-banner.png'
            ],
        ];
        foreach($entries as $entry){
            Category::updateOrCreate([
                'title'=>$entry['title'],
                'image_path'=>$entry['imageName'],
                'active' => 1
            ]);
        }

    }
}
