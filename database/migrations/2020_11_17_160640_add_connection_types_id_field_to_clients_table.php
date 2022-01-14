<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConnectionTypesIdFieldToClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->foreignId('category_id')
                ->nullable()
                ->after('role')
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('connection_type_id')
                ->after('category_id')
                ->nullable()
                ->constrained('connection_types')
                ->nullOnDelete();
            $table->string('connection_type_link')
                ->after('connection_type_id')
                ->nullable()
                ->comment('Ссылка на соц. сеть');
            $table->string('curator_contacts')
                ->after('connection_type_link')
                ->nullable()->comment('Контакты опекуна');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign('clients_connection_type_id_foreign');
            $table->dropColumn('connection_type_id');
            $table->dropColumn('connection_type_link');
        });
    }
}
