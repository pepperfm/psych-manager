<?php

namespace Database\Seeders;

use App\Models\Api\Admin\ConnectionType;
use Illuminate\Database\Seeder;

class ConnectionTypesSeeder extends Seeder
{
    private $types = [
        'Телефон',
        'Email',
        'ВКонтакте',
        'Viber',
        'WhatsApp',
        'Telegram',
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->types as $type) {
            ConnectionType::updateOrCreate(['name' => $type], ['name' => $type]);
        }
    }
}
