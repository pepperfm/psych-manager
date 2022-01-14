<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Category;

class CategoriesSeeder extends Seeder
{
    /** @var array $names */
    private array $names = [
        1 => ['Индивидуальная терапия', 'Парная терапия', 'Семейная терапия',], // Кол-во участников
        2 => ['Консультация', 'Терапия',], // Кол-во встреч
        3 => ['Коучинг'], // Характер вмешательства
        4 => ['Дети', 'Взрослые',], // Возраст
        5 => ['ОКР', 'Депрессия', 'СДВГ',], // Проблема/диагноз
        // 'Пролонг'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        foreach ($this->names as $names) {
            foreach ($names as $name) {
                Category::n()->setName($name)->save();
            }
        }
    }
}
