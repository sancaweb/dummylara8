<?php

namespace Database\Seeders;

use App\Models\Post\Category;
use Carbon\Carbon;
use App\Models\Post\Post;
use App\Models\Post\Tag;
use Faker\Factory as faker;
use Illuminate\Database\Seeder;


class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = faker::create('id_ID');

        $categories = [
            'pertandingan', 'kompetisi', 'pemain', 'club'
        ];
        foreach ($categories as $cat) {
            Category::create([
                'name' => $cat
            ]);
        }

        $tags = [
            'berita bola', 'berita kompetisi', 'kabar pemain', 'kabar club'
        ];

        foreach ($tags as $tag) {
            Tag::create([
                'name' => $tag
            ]);
        }

        $status = [
            'published', 'draft'
        ];

        $faker = faker::create('id_ID');

        $getCats = collect(Category::all()->modelKeys());
        $getTags = collect(Tag::all()->modelKeys());

        for ($i = 0; $i < 5; $i++) {
            $selectedTags = $getTags->random(rand(1, 4));

            $post = Post::create([
                'title' => $faker->sentence(5),
                'content' => $faker->text(),
                'category_id' => $getCats->random(),
                // 'status' => $status[rand(0, 1)],
                'status' => "published",
                'published_date' => Carbon::parse($faker->dateTimeThisMonth())->format('Y-m-d H:i:s')
            ]);
            $post->tags()->attach($selectedTags);
        }
    }
}
