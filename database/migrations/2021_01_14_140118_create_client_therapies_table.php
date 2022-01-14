<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientTherapiesTable extends Migration
{
    public function up(): void
    {
        Schema::create('client_therapies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->tinyInteger('problem_severity')->nullable()->comment('Степень тяжести болезни');
            $table->text('plan')->nullable()->comment('План на терапию');
            $table->text('request')->nullable()->comment('Запрос клиента');
            $table->text('notes')->nullable()->comment('Заметки по киленту');
            $table->text('concept_vision')->nullable()->comment('"Моя концептуализация"');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_therapies');
    }
}
