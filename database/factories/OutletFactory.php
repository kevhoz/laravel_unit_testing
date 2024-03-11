<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OutletFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama_outlet' => $this->faker->word,
            'lokasi_outlet' => $this->faker->address,
            'pic_outlet' => $this->faker->name,
        ];
    }
}
