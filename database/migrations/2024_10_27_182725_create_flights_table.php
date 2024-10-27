<?php

use Illuminate\Database\Migrations\Migration;
use App\Services\Migration\MigrationService;
use App\Services\Migration\FieldConfig;
use App\Services\Migration\FieldType;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $migrationService = new MigrationService();

        $migrationService->updateTable('flows', [
            new FieldConfig(['field' => 'type', 'type' => FieldType::STRING, 'length' => 100, 'nullable' => true]),
            new FieldConfig(['field' => 'private', 'type' => FieldType::BOOLEAN]),
            new FieldConfig(['field' => 'archive', 'type' => FieldType::BOOLEAN, 'default' => false]),
            new FieldConfig(['field' => 'user_id', 'type' => FieldType::INTEGER]),
            new FieldConfig(['field' => 'hash', 'type' => FieldType::STRING, 'length' => 64]),
            new FieldConfig(['field' => 'ref', 'type' => FieldType::STRING, 'length' => 64]),
            new FieldConfig(['field' => 'name', 'type' => FieldType::STRING, 'length' => 64]),
            new FieldConfig(['field' => 'logo', 'type' => FieldType::TEXT, 'nullable' => true]),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
