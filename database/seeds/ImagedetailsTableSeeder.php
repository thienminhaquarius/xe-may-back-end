<?php
use App\Imagedetail;
use Illuminate\Database\Seeder;

class ImagedetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('imagedetails')->delete();
        $json = File::get("database/data-sample/imagedetails.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            Imagedetail::create(array(
                'src' => $obj->src,
                'bikedetail_id' => $obj->bikedetail_id,

            ));
        }
    }
}
