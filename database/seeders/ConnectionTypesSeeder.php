<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Enums\ConnectionTypeEnum;

use App\Models\ConnectionType;

class ConnectionTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        foreach (ConnectionTypeEnum::cases() as $type) {
            ConnectionType::q()->updateOrCreate(['name' => $type]);
        }
    }
}
