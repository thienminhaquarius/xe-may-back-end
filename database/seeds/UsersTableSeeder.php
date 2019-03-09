<?php

use App\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        DB::table('users')->delete();
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

    }
}
