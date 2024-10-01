<?php

namespace App\Services\Migration;

class FieldConfig
{
    private string $field;
    private string $type;
    private bool $nullable;
    private int | null $length;


    /**
     * @param array{
     *     field: string,
     *     type: FieldType,
     *     nullable?: bool,
     *     length?: int,
     * } $config
     */

    public function __construct(array $config)
    {
        $this->field = $config['field'];
        $this->type = $config['type'];
        $this->nullable = $config['nullable'] ?? false;
        $this->length = $config['length'] ?? null;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function getLength(): int | null
    {
        return $this->length;
    }
}
