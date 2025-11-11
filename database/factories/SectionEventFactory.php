<?php

namespace Database\Factories;

use App\Models\Section;
use App\Models\SectionEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SectionEvent>
 */
class SectionEventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SectionEvent::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('now', '+6 months');
        $endDate = $this->faker->optional()->dateTimeBetween($startDate, '+1 day');
        
        return [
            'section_id' => Section::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraphs(2, true),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'location' => $this->faker->address(),
            'event_type' => $this->faker->randomElement(['meeting', 'rally', 'social', 'technical']),
            'status' => $this->faker->randomElement(['upcoming', 'ongoing', 'completed', 'cancelled']),
            'max_participants' => $this->faker->optional()->numberBetween(10, 200),
            'registration_fee' => $this->faker->optional()->randomFloat(2, 0, 100),
            'additional_info' => $this->faker->optional()->paragraph(),
            'contact_email' => $this->faker->optional()->email(),
            'contact_phone' => $this->faker->optional()->phoneNumber(),
            'is_featured' => $this->faker->boolean(20), // 20% chance of being featured
            'is_active' => $this->faker->boolean(90), // 90% chance of being active
        ];
    }

    /**
     * Indicate that the event is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    /**
     * Indicate that the event is not featured.
     */
    public function notFeatured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => false,
        ]);
    }

    /**
     * Indicate that the event is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the event is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the event is upcoming.
     */
    public function upcoming(): static
    {
        return $this->state(fn (array $attributes) => [
            'start_date' => $this->faker->dateTimeBetween('now', '+6 months'),
            'status' => 'upcoming',
        ]);
    }

    /**
     * Indicate that the event is ongoing.
     */
    public function ongoing(): static
    {
        return $this->state(fn (array $attributes) => [
            'start_date' => $this->faker->dateTimeBetween('-1 day', 'now'),
            'end_date' => $this->faker->dateTimeBetween('now', '+1 day'),
            'status' => 'ongoing',
        ]);
    }

    /**
     * Indicate that the event is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'start_date' => $this->faker->dateTimeBetween('-6 months', '-1 day'),
            'end_date' => $this->faker->dateTimeBetween('-1 day', 'now'),
            'status' => 'completed',
        ]);
    }

    /**
     * Indicate that the event is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }

    /**
     * Indicate that the event is a meeting.
     */
    public function meeting(): static
    {
        return $this->state(fn (array $attributes) => [
            'event_type' => 'meeting',
        ]);
    }

    /**
     * Indicate that the event is a rally.
     */
    public function rally(): static
    {
        return $this->state(fn (array $attributes) => [
            'event_type' => 'rally',
        ]);
    }

    /**
     * Indicate that the event is a social event.
     */
    public function social(): static
    {
        return $this->state(fn (array $attributes) => [
            'event_type' => 'social',
        ]);
    }

    /**
     * Indicate that the event is a technical event.
     */
    public function technical(): static
    {
        return $this->state(fn (array $attributes) => [
            'event_type' => 'technical',
        ]);
    }
} 