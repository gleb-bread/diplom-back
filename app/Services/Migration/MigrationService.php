<?php

namespace App\Services\Migration;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use App\Services\Migration\FieldConfig;
use App\Services\Migration\UpdateOrCreateFieldConfig;
use App\Services\Migration\AdditionalFieldsConfig;

class MigrationService
{
    /**
     * Метод для обновления или создания столбцов в таблице.
     *
     * @param string $tableName
     * @param FieldConfig[] $fields
     * @param AdditionalFieldsConfig? $additionalFields
     */
    public function updateTable(string $tableName, array $fields, AdditionalFieldsConfig | null $additionalFields = null): void
    {

        $additionalFieldsConfig = $additionalFields ?? new AdditionalFieldsConfig();

        if (Schema::hasTable($tableName)) {
            Schema::table($tableName, function (Blueprint $table) use ($fields, $additionalFieldsConfig) {
                foreach ($fields as $config) {
                    $updateConfig = new UpdateOrCreateFieldConfig($table, $config);

                    $this->updateOrCreateField($updateConfig);
                }

                $this->createAdditionalFields($table, $additionalFieldsConfig);
            });
        } else {
            Schema::create($tableName, function (Blueprint $table) use ($fields, $additionalFieldsConfig) {
                foreach ($fields as $config) {
                    $updateConfig = new UpdateOrCreateFieldConfig($table, $config);

                    $this->updateOrCreateField($updateConfig);
                }

                $this->createAdditionalFields($table, $additionalFieldsConfig);
            });
        }
    }

    private function updateOrCreateField(UpdateOrCreateFieldConfig $config)
    {
        $tableName = $config->getTable()->getTable();
        $field = $config->getField();

        if (!Schema::hasColumn($tableName, $field)) {
            $this->addField($config);
        } else {
            $this->updateField($config);
        }
    }

    private function addField(UpdateOrCreateFieldConfig $config): void
    {
        $nullable = $config->isNullable();
        $length = $config->getLength();
        $column = $this->getColumnByType($config, $length);

        if ($nullable) {
            $column->nullable();
        }
    }


    private function updateField(UpdateOrCreateFieldConfig $config): void
    {

        $table = $config->getTable();
        $field = $config->getField();
        $type = $config->getType();
        $length = $config->getLength();
        $nullable = $config->isNullable();

        $currentType = DB::getSchemaBuilder()->getColumnType($table->getTable(), $field);

        if ($currentType !== $type) {
            $column = $this->getColumnByType($config, $length);

            if ($nullable) {
                $column->nullable()->change();
            } else {
                $column->change();
            }
        }
    }

    private function getColumnByType(UpdateOrCreateFieldConfig $config, int | null $length = null)
    {
        $table = $config->getTable();
        $field = $config->getField();
        $type = $config->getType();

        switch ($type) {
            case 'string':
                return $table->string($field, $length);
            case 'boolean':
                return $table->boolean($field)->default(true);
            case 'integer':
                return $table->integer($field);
            case 'char':
                return $table->char($field, $length);
            case 'text':
                return $table->text($field);
            case 'timestamp':
                return $table->timestamp($field);
            case 'datetime':
                return $table->dateTime($field);
            case 'json':
                return $table->json($field);
            case 'float':
                return $table->float($field);
            default:
                throw new \InvalidArgumentException("Unsupported field type: $type");
        }
    }

    private function createAdditionalFields(Blueprint $table, AdditionalFieldsConfig $config)
    {
        $idFieldName = $config->getIdName() ?? 'id';
        $tableName = $table->getTable();
        $timestapmsFileds = ['created_at', 'updated_at'];
        $softDeletes = 'deleted_at';
        $resultTimestamps = true;

        if ($config->getIdExist())
            if (!Schema::hasColumn($tableName, $idFieldName))
                $this->createIdField($table, $idFieldName);


        if ($config->getTimestamps()) {
            foreach ($timestapmsFileds as $timestamp) {
                if (Schema::hasColumn($tableName, $timestamp)) {
                    $resultTimestamps = $resultTimestamps && false;
                }
            }

            if ($resultTimestamps) {
                $this->createTimestampsFields($table);
            }

            if (!$resultTimestamps)
                foreach ($timestapmsFileds as $timestamp) {
                    if (!Schema::hasColumn($tableName, $timestamp)) {
                        $field = new FieldConfig(['field' => $timestamp, 'type' => FieldType::TIMESTAMP, 'nullable' => true]);
                        $updateConfig = new UpdateOrCreateFieldConfig($table, $field);
                        $this->updateOrCreateField($updateConfig);
                    }
                }
        }

        if ($config->getSoftDeletes()) {
            if (!Schema::hasColumn($tableName, $softDeletes))
                $this->createSoftDeletes($table);
        }
    }

    private function createIdField(Blueprint $table, string $idName)
    {
        $table->id($idName);
    }

    private function createTimestampsFields(Blueprint $table)
    {
        $table->timestamps();
    }

    private function createSoftDeletes(Blueprint $table)
    {
        $table->softDeletes();
    }
}
