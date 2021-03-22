<?php

namespace Database\Seeders;

use App\Models\Api\Admin\UserTherapy;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Session;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->has(
                Session::factory()
                    ->count(5)
                    ->state(function (array $attributes, User $user) {
                        return [
                            'doctor_id' => $user->doctor_id
                        ];
                    })
            )
            ->has(
                UserTherapy::factory()
                    ->count(1)
                    ->state(function (array $attributes, User $user) {
                        return [
                            'user_id' => $user->id
                        ];
                    }), 'therapy'
            )
            ->count(100)
            ->create();
    }
}
