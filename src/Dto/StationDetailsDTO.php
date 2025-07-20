<?php

namespace App\Dto;

class StationDetailsDTO
{
    public function __construct(
        public int $id,
        public string $stationId,
        public string $name,
        public string $wmoId,
        public string $beginDate,
        public string $endDate,
        public string $latitude,
        public string $longitude,
        public string $gauss1,
        public string $gauss2,
        public string $geogr1,
        public string $geogr2,
        public string $elevation,
        public string $elevationPressure,
    ) {}
}
