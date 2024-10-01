<?php

namespace App\Services\Migration;

use Illuminate\Database\Schema\Blueprint;
use App\Services\Migration\FieldConfig;

class UpdateOrCreateFieldConfig
{
    private Blueprint $table;
    private FieldConfig $fieldConfig;

    public function __construct(Blueprint $table, FieldConfig $config)
    {
        $this->fieldConfig = $config;
        $this->table = $table;
    }

    public function getTable(): Blueprint
    {
        return $this->table;
    }

    public function getField(): string
    {
        return $this->fieldConfig->getField();
    }

    public function getType(): string
    {
        return $this->fieldConfig->getType();
    }

    public function getLength(): int | null
    {
        return $this->fieldConfig->getLength();
    }

    public function isNullable(): bool
    {
        return $this->fieldConfig->isNullable();
    }
}
