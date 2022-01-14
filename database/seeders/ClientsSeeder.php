<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Client;
use App\Models\Session;
use App\Models\ClientTherapy;

class ClientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // todo: without global scopes (somehow)
        Client::factory()
            ->has(
                Session::factory()
                    ->count(5)
                    ->state(function (array $attributes, Client $user) {
                        return [
                            'user_id' => $user->user_id
                        ];
                    })
            )
            ->has(
                ClientTherapy::factory()
                    ->count(1)
                    ->state(function (array $attributes, Client $user) {
                        return [
                            'client_id' => $user->id
                        ];
                    }), 'therapy'
            )
            ->count(100)
            ->create();
    }
}
