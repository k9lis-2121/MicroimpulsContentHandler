<?php

namespace App\DTO;

class SearchDTO
{
    public $name;
    public $kpId;
    public $transcodeStatus;
    public $semantic = true;

    public function __construct(array $data)
    {
        $this->name = $data['searchName'] ?? null;
        $this->kpId = $data['searchKpId'] ?? null;
        $this->transcodeStatus = $data['searchTranscodingStatus'] ?? null;
        $this->smartyId = $data['smartyId'] ?? null; // Инициализация нового свойства
    }
}