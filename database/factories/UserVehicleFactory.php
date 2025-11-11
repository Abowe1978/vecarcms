<?php

namespace Database\Factories;

use App\Models\UserVehicle;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserVehicleFactory extends Factory
{
    protected $model = UserVehicle::class;

    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'vehicle_id' => Vehicle::inRandomOrder()->first()?->id ?? Vehicle::factory(),
            'registration_year' => $this->faker->year,
            'plate_number' => strtoupper($this->faker->bothify('??####')),
            'color' => $this->faker->safeColorName,
            'vin' => strtoupper($this->faker->bothify('??#################')),
        ];
    }
} 