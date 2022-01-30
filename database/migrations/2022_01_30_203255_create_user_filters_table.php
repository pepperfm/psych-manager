<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserFiltersTable extends Migration
{
    public function up(): void
    {
        Schema::create('user_filters', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->comment('Идентификатор пользователя')->constrained();
            $table->string('module')->comment('Идентификато модуля');
            $table->longText('value')->nullable()->comment('Значение фильтров');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_filters');
    }
}
