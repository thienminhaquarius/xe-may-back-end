<?php

namespace App\Http\Controllers\API;

use App\Bike;
use App\Bikedetail;
use App\Comment;
use App\Http\Controllers\Controller;
use App\Rating;
use App\User;
use DB;
use Faker\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Admin extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faker = Factory::create();

        // return $faker->firstName;

        //user
        // DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('users')->truncate();
        DB::table('users')->insert([
            'name' => 'Boss',
            'email' => 'boss@gmail.com',
            'password' => bcrypt('123456'),
        ]);

        // random user
        for ($i = 2; $i <= 20; $i++) {
            $user = User::create([
                'name' => $faker->firstName,
                'email' => $faker->email,
                'password' => bcrypt($faker->text($maxNbChars = 7)),
            ]);
        }

        //seed image
        $defaultFolder = 'defaultImage/';
        $imageFolder = 'productThumbnailImage/';

        $listDefault = Storage::disk('upload_image')->files($defaultFolder);
        $listImageFolder = Storage::disk('upload_image')->files($imageFolder);

        //delete all file
        foreach ($listImageFolder as $value) {
            Storage::disk('upload_image')->delete($value);
        }

        // move file
        foreach ($listDefault as $value) {
            $changedPath = str_replace($defaultFolder, $imageFolder, $value);
            Storage::disk('upload_image')->copy($value, $changedPath);
        }

        //Seed bike
        $listImgName = str_replace($defaultFolder, '', $listDefault);
        DB::table('bikes')->truncate();
        foreach ($listImgName as $imgName) {
            Bike::create(array(
                'name' => $faker->firstNameMale,
                'price' => $faker->numberBetween($min = 50, $max = 9000),
                'thumbnailImage' => $imgName,
                'user_id' => $faker->numberBetween($min = 1, $max = 20),
            ));
        }

        //see bikedetail
        DB::table('bikedetails')->truncate();
        for ($i = 1; $i < count($listImgName); $i++) {
            Bikedetail::create(array(
                'info' => $faker->text($maxNbChars = 150),
                'bike_id' => $i,
            ));
        }

        //comment
        DB::table('comments')->truncate();
        for ($i = 0; $i < 100; $i++) {
            Comment::create(array(
                'title' => $faker->text($maxNbChars = 10),
                'content' => $faker->text($maxNbChars = 30),
                'user_id' => $faker->numberBetween($min = 1, $max = 20),
                'bike_id' => $faker->numberBetween($min = 1, $max = 20),
            ));
        }

        DB::table('ratings')->truncate();
        for ($i = 0; $i < 100; $i++) {
            Rating::create(array(
                'value' => $faker->numberBetween($min = 1, $max = 5),
                'bike_id' => $faker->numberBetween($min = 1, $max = 20),
                'user_id' => $faker->numberBetween($min = 1, $max = 20),
            ));
        }

        return response()->json([
            'Status' => 'Success',
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
