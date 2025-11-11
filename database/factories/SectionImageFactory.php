<?php

namespace Database\Factories;

use App\Models\Section;
use App\Models\SectionImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SectionImage>
 */
class SectionImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SectionImage::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'section_id' => Section::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'image_path' => 'sections/1/images/' . $this->faker->uuid() . '.jpg',
            'image_type' => $this->faker->randomElement(['gallery', 'banner', 'logo']),
            'sort_order' => $this->faker->numberBetween(0, 100),
            'is_featured' => $this->faker->boolean(20), // 20% chance of being featured
            'is_active' => $this->faker->boolean(90), // 90% chance of being active
        ];
    }

    /**
     * Indicate that the image is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    /**
     * Indicate that the image is not featured.
     */
    public function notFeatured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => false,
        ]);
    }

    /**
     * Indicate that the image is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the image is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the image is a gallery image.
     */
    public function gallery(): static
    {
        return $this->state(fn (array $attributes) => [
            'image_type' => 'gallery',
        ]);
    }

    /**
     * Indicate that the image is a banner image.
     */
    public function banner(): static
    {
        return $this->state(fn (array $attributes) => [
            'image_type' => 'banner',
        ]);
    }

    /**
     * Indicate that the image is a logo.
     */
    public function logo(): static
    {
        return $this->state(fn (array $attributes) => [
            'image_type' => 'logo',
        ]);
    }
} 