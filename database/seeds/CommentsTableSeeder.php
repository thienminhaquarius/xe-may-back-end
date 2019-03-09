<?php
use App\Comment;
use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('comments')->delete();
        $json = File::get("database/data-sample/comments.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            Comment::create(array(
                'title' => $obj->title,
                'content' => $obj->content,
                'user_id' => $obj->user_id,
                'bike_id' => $obj->bike_id,
            ));
        }
    }
}
