<?php

namespace Database\Factories;

use App\Models\UserVehiclePhoto;
use App\Models\UserVehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserVehiclePhotoFactory extends Factory
{
    protected $model = UserVehiclePhoto::class;

    public function definition()
    {
        return [
            'user_vehicle_id' => UserVehicle::inRandomOrder()->first()?->id ?? UserVehicle::factory(),
            'path' => 'user_vehicle_photos/' . $this->faker->uuid . '.jpg',
            'is_primary' => $this->faker->boolean(30),
        ];
    }
} 