<?php
use App\Bikedetail;
use Illuminate\Database\Seeder;

class BikedetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('bikedetails')->delete();
        $json = File::get("database/data-sample/bikedetails.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            Bikedetail::create(array(
                'info' => $obj->info,
                'bike_id' => $obj->bike_id,
            ));
        }
    }
}
