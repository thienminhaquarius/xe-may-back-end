<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ImageFolderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
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

    }
}
