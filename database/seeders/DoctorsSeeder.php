<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Api\Admin\Doctor;

class DoctorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Doctor::create([
            'name' => 'visitor',
            'email' => 'visitor@yandex.ru',
            'password' => 123456,
            'gender' => Doctor::GENDER_FEMALE,
            'phone' => '1234',
        ]);
        Doctor::create([
            'name' => 'Psych',
            'email' => 'psych@yandex.ru',
            'password' => 'psych@yandex.ru',
            'gender' => Doctor::GENDER_FEMALE,
            'phone' => '88005553535',
        ]);
        Doctor::create([
            'name' => 'Psych',
            'email' => 'psych_j@yandex.ru',
            'password' => 'psych@yandex.ru',
            'gender' => Doctor::GENDER_FEMALE,
            'phone' => '88005553538',
        ]);

        // OAuth user for a passport
        \Laravel\Passport\Client::query()->create([
            'id' => config('passport.personal_access_client.id'),
            'name' => 'Laravel Password Grant Client',
            'secret' => config('passport.personal_access_client.secret'),
            'redirect' => 'http://localhost',
            'personal_access_client' => 0,
            'password_client' => 1,
            'revoked' => 0,
        ]);

        \Illuminate\Support\Facades\Artisan::call('passport:install');
    }
}
