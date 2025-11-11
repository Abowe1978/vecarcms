<?php

namespace Database\Factories;

use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Section>
 */
class SectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Section::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->city() . ' Section';
        
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'country' => $this->faker->country(),
            'postal_code' => $this->faker->postcode(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),
            'website' => $this->faker->url(),
            'is_active' => $this->faker->boolean(80), // 80% chance of being active
        ];
    }

    /**
     * Indicate that the section is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the section is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the section has coordinates.
     */
    public function withCoordinates(): static
    {
        return $this->state(fn (array $attributes) => [
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
        ]);
    }

    /**
     * Indicate that the section has no coordinates.
     */
    public function withoutCoordinates(): static
    {
        return $this->state(fn (array $attributes) => [
            'latitude' => null,
            'longitude' => null,
        ]);
    }

    /**
     * Indicate that the section has contact information.
     */
    public function withContactInfo(): static
    {
        return $this->state(fn (array $attributes) => [
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),
            'website' => $this->faker->url(),
        ]);
    }

    /**
     * Indicate that the section has no contact information.
     */
    public function withoutContactInfo(): static
    {
        return $this->state(fn (array $attributes) => [
            'phone' => null,
            'email' => null,
            'website' => null,
        ]);
    }
} 