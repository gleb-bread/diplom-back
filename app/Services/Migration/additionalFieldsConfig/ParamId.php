<?php

namespace App\Services\Migration\additionalFieldsConfig;

class ParamId
{
    private string | null $name;
    private bool $exist;

    /**
     * @param array{
     *     name?: string,
     *     exist?: bool,
     * } $config
     */

    public function __construct(array | null $config = null)
    {
        $this->name = $config['name'] ?? null;
        $this->exist = $config['exist'] ?? true;
    }

    public function getName(): string | null
    {
        return $this->name;
    }

    public function getExist(): bool
    {
        return $this->exist;
    }
}
