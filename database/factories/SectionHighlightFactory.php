<?php

namespace Database\Factories;

use App\Models\Section;
use App\Models\SectionHighlight;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SectionHighlight>
 */
class SectionHighlightFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SectionHighlight::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $validFrom = $this->faker->optional()->dateTimeBetween('-1 month', 'now');
        $validUntil = $this->faker->optional()->dateTimeBetween('now', '+6 months');
        
        return [
            'section_id' => Section::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'icon' => $this->faker->randomElement([
                'fas fa-star', 'fas fa-heart', 'fas fa-info-circle', 
                'fas fa-exclamation-triangle', 'fas fa-calendar', 'fas fa-phone'
            ]),
            'button_text' => $this->faker->optional()->randomElement(['Learn More', 'Read More', 'Contact Us', 'Join Now']),
            'button_url' => $this->faker->optional()->url(),
            'button_type' => $this->faker->randomElement(['primary', 'secondary', 'success', 'warning', 'danger']),
            'highlight_type' => $this->faker->randomElement(['info', 'success', 'warning', 'event', 'contact']),
            'background_color' => $this->faker->optional()->hexColor(),
            'text_color' => $this->faker->optional()->hexColor(),
            'sort_order' => $this->faker->numberBetween(0, 100),
            'is_active' => $this->faker->boolean(90), // 90% chance of being active
            'valid_from' => $validFrom,
            'valid_until' => $validUntil,
        ];
    }

    /**
     * Indicate that the highlight is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the highlight is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the highlight is currently valid.
     */
    public function valid(): static
    {
        return $this->state(fn (array $attributes) => [
            'valid_from' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'valid_until' => $this->faker->dateTimeBetween('now', '+6 months'),
        ]);
    }

    /**
     * Indicate that the highlight is not yet valid.
     */
    public function future(): static
    {
        return $this->state(fn (array $attributes) => [
            'valid_from' => $this->faker->dateTimeBetween('now', '+1 month'),
            'valid_until' => $this->faker->dateTimeBetween('+1 month', '+6 months'),
        ]);
    }

    /**
     * Indicate that the highlight has expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'valid_from' => $this->faker->dateTimeBetween('-6 months', '-2 months'),
            'valid_until' => $this->faker->dateTimeBetween('-2 months', '-1 day'),
        ]);
    }

    /**
     * Indicate that the highlight has no validity period.
     */
    public function alwaysValid(): static
    {
        return $this->state(fn (array $attributes) => [
            'valid_from' => null,
            'valid_until' => null,
        ]);
    }

    /**
     * Indicate that the highlight is of info type.
     */
    public function info(): static
    {
        return $this->state(fn (array $attributes) => [
            'highlight_type' => 'info',
            'icon' => 'fas fa-info-circle',
        ]);
    }

    /**
     * Indicate that the highlight is of success type.
     */
    public function success(): static
    {
        return $this->state(fn (array $attributes) => [
            'highlight_type' => 'success',
            'icon' => 'fas fa-check-circle',
            'button_type' => 'success',
        ]);
    }

    /**
     * Indicate that the highlight is of warning type.
     */
    public function warning(): static
    {
        return $this->state(fn (array $attributes) => [
            'highlight_type' => 'warning',
            'icon' => 'fas fa-exclamation-triangle',
            'button_type' => 'warning',
        ]);
    }

    /**
     * Indicate that the highlight is of event type.
     */
    public function event(): static
    {
        return $this->state(fn (array $attributes) => [
            'highlight_type' => 'event',
            'icon' => 'fas fa-calendar',
            'button_type' => 'primary',
        ]);
    }

    /**
     * Indicate that the highlight is of contact type.
     */
    public function contact(): static
    {
        return $this->state(fn (array $attributes) => [
            'highlight_type' => 'contact',
            'icon' => 'fas fa-phone',
            'button_type' => 'secondary',
        ]);
    }

    /**
     * Indicate that the highlight has a primary button.
     */
    public function primaryButton(): static
    {
        return $this->state(fn (array $attributes) => [
            'button_type' => 'primary',
        ]);
    }

    /**
     * Indicate that the highlight has a secondary button.
     */
    public function secondaryButton(): static
    {
        return $this->state(fn (array $attributes) => [
            'button_type' => 'secondary',
        ]);
    }

    /**
     * Indicate that the highlight has a success button.
     */
    public function successButton(): static
    {
        return $this->state(fn (array $attributes) => [
            'button_type' => 'success',
        ]);
    }

    /**
     * Indicate that the highlight has a warning button.
     */
    public function warningButton(): static
    {
        return $this->state(fn (array $attributes) => [
            'button_type' => 'warning',
        ]);
    }

    /**
     * Indicate that the highlight has a danger button.
     */
    public function dangerButton(): static
    {
        return $this->state(fn (array $attributes) => [
            'button_type' => 'danger',
        ]);
    }
} 