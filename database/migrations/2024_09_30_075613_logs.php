<?php

use Illuminate\Database\Migrations\Migration;
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
        $service = new MigrationService();

        $service->updateTable('logs', [
            new FieldConfig(['field' => 'user_id', 'type' => FieldType::INTEGER, 'nullable' => true]),
            new FieldConfig(['field' => 'method', 'type' => FieldType::CHAR, 'length' => 6]),
            new FieldConfig(['field' => 'title', 'type' => FieldType::STRING, 'length' => 256]),
            new FieldConfig(['field' => 'time', 'type' => FieldType::FLOAT]),
            new FieldConfig(['field' => 'status', 'type' => FieldType::INTEGER]),
            new FieldConfig(['field' => 'payload', 'type' => FieldType::JSON]),
            new FieldConfig(['field' => 'headers', 'type' => FieldType::JSON])
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
