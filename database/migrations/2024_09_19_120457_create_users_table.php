<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Services\Migration\MigrationService;
use App\Services\Migration\FieldConfig;
use App\Services\Migration\FieldType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $migrationService = new MigrationService();

        $migrationService->updateTable('users', [
            new FieldConfig(['field' => 'login', 'type' => FieldType::STRING, 'length' => 100, 'nullable' => false]),
            new FieldConfig(['field' => 'name', 'type' => FieldType::STRING, 'length' => 100, 'nullable' => true]),
            new FieldConfig(['field' => 'email', 'type' => FieldType::STRING, 'length' => 100, 'nullable' => false]),
            new FieldConfig(['field' => 'password', 'type' => FieldType::TEXT, 'nullable' => false]),
            new FieldConfig(['field' => 'second_name', 'type' => FieldType::STRING, 'length' => 100, 'nullable' => true]),
            new FieldConfig(['field' => 'patronymic', 'type' => FieldType::STRING, 'length' => 100, 'nullable' => true]),
            new FieldConfig(['field' => 'delayed', 'type' => FieldType::BOOLEAN, 'nullable' => false]),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
