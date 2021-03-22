<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Api\Admin\User;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->index()->nullable()->constrained()->nullOnDelete();
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique()->nullable();
            $table->date('birthday_date')->nullable();
            $table->string('password')->nullable();
            $table->boolean('gender')->nullable();
            $table->tinyInteger('role')->default(User::ROLE_CLIENT);
            $table->boolean('meeting_type')->nullable()->default(true)->comment('Фомарт общения: онлайн или офлайн');
            $table->string('access_token')->nullable()->default(null)->comment('Токен авторизации');
            $table->timestamp('access_token_expire')->nullable()->default(null)->comment('Время истечения жизни токена');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
