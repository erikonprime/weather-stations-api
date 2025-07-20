<?php

namespace App\Dto;

class StationDTO
{
    public function __construct(
        public string $stationId,
        public string $name,
    ) {}
}
