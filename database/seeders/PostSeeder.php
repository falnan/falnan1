<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Segment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $keluarga = Segment::where('slug', 'keluarga')->first();
        $teman = Segment::where('slug', 'teman')->first();

        Post::factory()->count(10)->create()
            ->each(function (Post $post) use ($keluarga, $teman) {
                $segments = collect([$keluarga->id, $teman->id])
                    ->random(fake()->numberBetween(1, 2))
                    ->all();

                $post->segments()->sync($segments);
            });
    }
}
