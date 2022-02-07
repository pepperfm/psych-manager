<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Enums\GenderEnum;

use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        User::create([
            'name' => 'Dmitry',
            'email' => 'Damon3453@yandex.ru',
            'password' => 41526378,
            'gender' => GenderEnum::MALE->value,
            'phone' => '89045352628',
        ]);
        User::create([
            'name' => 'Natalie',
            'email' => 'nataliemaslova@mail.ru',
            'password' => 'nataliemaslova@mail.ru',
            'gender' => GenderEnum::FEMALE->value,
            'phone' => '89182702625',
        ]);
        User::create([
            'name' => 'visitor',
            'email' => 'visitor@yandex.ru',
            'password' => 123456,
            'gender' => GenderEnum::FEMALE->value,
            'phone' => '1234',
        ]);
        User::create([
            'name' => 'Psych',
            'email' => 'psych@yandex.ru',
            'password' => 'psych@yandex.ru',
            'gender' => GenderEnum::FEMALE->value,
            'phone' => '88005553535',
        ]);
        User::create([
            'name' => 'J',
            'email' => 'psych_j@yandex.ru',
            'password' => 'psych_j@yandex.ru',
            'gender' => GenderEnum::FEMALE->value,
            'phone' => '88005553538',
        ]);
    }
}
