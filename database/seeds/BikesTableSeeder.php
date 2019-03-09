<?php

use App\Bike;
use Illuminate\Database\Seeder;

class BikesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bikes')->delete();
        $json = File::get("database/data-sample/bikes.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            Bike::create(array(
                'name' => $obj->name,
                'price' => $obj->price,
                'thumbnailImage' => $obj->thumbnailImage,
                'user_id' => $obj->user_id,
            ));
        }
    }
}
