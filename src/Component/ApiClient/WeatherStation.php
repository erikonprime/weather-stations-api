<?php

namespace App\Component\ApiClient;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherStation
{
    private const STATIONS_RESOURCE_ID = 'c32c7afd-0d05-44fd-8b24-1de85b4bf11d';

    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly string $apiUrl,
    ) {}

    public function fetchStations(): array
    {
        try {
            $response = $this->client->request('GET', $this->apiUrl, [
                'query' => [
                    'resource_id' => self::STATIONS_RESOURCE_ID,
                ],
            ]);

            return $response->toArray();
        } catch (\Throwable $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }

    public function fetchStation(string $stationId): array
    {
        $response = $this->client->request('GET', $this->apiUrl, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode([
                'resource_id' => self::STATIONS_RESOURCE_ID,
                'filters' => [
                    'STATION_ID' => [$stationId],
                ],
            ]),
        ]);

        return $response->toArray();
    }
}
