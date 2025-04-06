<?php

namespace Database\Factories;

use App\Enums\Album\Active;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Album>
 */
class AlbumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'song_name' => $this->faker->sentence(3),
            'artist_name' => $this->faker->name(),
            'cover_image' => 'https://placehold.co/600x400?text='.$this->faker->randomElement([
                'Classic+Rock',
                'Jazz+Vibes',
                'Pop+Hits',
                'Smooth+Soul',
                'Reggae+Beats',
                'Indie+Chill',
                'Alternative+Energy',
                'Blues+Groove',
                'Folk+Melodies',
                'Electronic+Dreams',
            ]),
            'active' => Active::YES,
        ];
    }
}
