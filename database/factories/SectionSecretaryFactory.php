<?php

namespace Database\Factories;

use App\Models\Section;
use App\Models\SectionSecretary;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SectionSecretary>
 */
class SectionSecretaryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SectionSecretary::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'section_id' => Section::factory(),
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'phone' => $this->faker->phoneNumber(),
            'mobile' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'role' => $this->faker->randomElement(['secretary', 'assistant', 'coordinator']),
            'bio' => $this->faker->paragraph(),
            'photo_path' => $this->faker->optional()->imageUrl(200, 200, 'people'),
            'is_primary' => $this->faker->boolean(10), // 10% chance of being primary
            'is_active' => $this->faker->boolean(90), // 90% chance of being active
            'appointed_date' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'term_end_date' => $this->faker->optional()->dateTimeBetween('now', '+2 years'),
        ];
    }

    /**
     * Indicate that the secretary is primary.
     */
    public function primary(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_primary' => true,
        ]);
    }

    /**
     * Indicate that the secretary is not primary.
     */
    public function notPrimary(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_primary' => false,
        ]);
    }

    /**
     * Indicate that the secretary is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the secretary is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the secretary has the secretary role.
     */
    public function secretary(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'secretary',
        ]);
    }

    /**
     * Indicate that the secretary has the assistant role.
     */
    public function assistant(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'assistant',
        ]);
    }

    /**
     * Indicate that the secretary has the coordinator role.
     */
    public function coordinator(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'coordinator',
        ]);
    }
} 