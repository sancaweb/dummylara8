<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Post\Tag;
use App\Models\Post\Post;
use Faker\Factory as faker;
use App\Models\Post\Category;
use Illuminate\Database\Seeder;
use Cviebrock\EloquentSluggable\Services\SlugService;


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
            'berita bola', 'berita kompetisi', 'kabar pemain', 'kabar club', 'berita umum',
            'berita pssi'
        ];

        foreach ($tags as $tag) {
            Tag::create([
                'name' => $tag
            ]);
        }

        // $dataTags = [];
        // for ($t = 0; $t < 5000; $t++) {
        //     $name = $faker->sentence(rand(1, 3));
        //     $slug = SlugService::createSlug(Tag::class, 'slug', $name);

        //     $dataTags[] = [
        //         'name' => rtrim($name, '.'),
        //         'slug' => $slug,
        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s'),
        //     ];
        // }

        // Tag::insert($dataTags);


        $status = [
            'published', 'draft'
        ];


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
