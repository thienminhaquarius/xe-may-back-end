<?php

use App\Rating;
use Illuminate\Database\Seeder;

class RatingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ratings')->delete();
        $json = File::get("database/data-sample/ratings.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            Rating::create(array(
                'value' => $obj->value,
                'bike_id' => $obj->bike_id,
                'user_id' => $obj->user_id,
            ));
        }

    }
}
