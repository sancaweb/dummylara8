<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Master\Page;
use Faker\Factory as faker;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = faker::create('id_ID');


        for ($i = 0; $i < 5; $i++) {
            Page::create([
                'title' => $faker->sentence(5),
                'content' => $faker->text(),
                'status' => "published",
                'published_date' => Carbon::parse($faker->dateTimeThisMonth())->format('Y-m-d H:i:s')
            ]);
        }
    }
}
