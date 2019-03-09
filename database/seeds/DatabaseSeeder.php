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
        $this->call(UsersTableSeeder::class);
        $this->call(BikesTableSeeder::class);
        $this->call(RatingsTableSeeder::class);
        $this->call(CommentsTableSeeder::class);
        $this->call(CommentsTableSeeder::class);
        $this->call(BikedetailsTableSeeder::class);
        $this->call(ImageFolderSeeder::class);

    }
}
