<?php

namespace App\Services\Migration;

use App\Services\Migration\additionalFieldsConfig\ParamId;

class AdditionalFieldsConfig
{
    private ParamId $id;
    private bool $timestamps;
    private bool $softDeletes;

    /**
     * @param array{
     *     id?: ParamId,
     *     timestamps?: bool,
     *     softDeletes?: bool,
     * } $config
     */


    public function __construct(array | null $config = null)
    {
        $this->timestamps = $config['timestamps'] ?? true;
        $this->softDeletes = $config['softDeletes'] ?? false;
        $this->id = isset($config['id']) ? $config['id'] : new ParamId();
    }

    public function getIdExist(): bool
    {
        return $this->id->getExist();
    }

    public function getIdName(): string | null
    {
        return $this->id->getName();
    }

    public function getTimestamps(): bool
    {
        return $this->timestamps;
    }

    public function getSoftDeletes()
    {
        return $this->softDeletes;
    }
}
