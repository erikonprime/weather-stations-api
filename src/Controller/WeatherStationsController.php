<?php

namespace App\Controller;

use App\Component\ApiClient\WeatherStation;
use App\Dto\StationDetailsDTO;
use App\Dto\StationDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

final class WeatherStationsController extends AbstractController
{
    #[Route('/', name: 'default_', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to simple weather station api!',
            'random_token' => bin2hex(random_bytes(32)),
        ]);
    }

    #[Route('/api/stations/list', name: 'get_stations_list', methods: ['GET'])]
    #[OA\Get(
        description: 'Returns list of all stations with Station_id and Name',
        summary: 'List all weather stations',
        security: [['Bearer' => []]],
        tags: ['stations'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: StationDTO::class)),
                ),
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function getStations(Request $request, WeatherStation $weatherStationClient): JsonResponse
    {
        $res = $weatherStationClient->fetchStations();

        $list = array_map(function ($station) {
            return new StationDTO($station['STATION_ID'], $station['NAME']);
        }, $res['result']['records'] ?? []);

        return $this->json($list);
    }

    #[Route('/api/stations/{id}/details', name: 'get_station_details', methods: ['GET'])]
    #[OA\Get(
        description: 'Station details by Station_id with all data fields found in data source',
        summary: 'Station details',
        security: [['Bearer' => []]],
        tags: ['stations'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Station id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string'),
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(ref: new Model(type: StationDetailsDTO::class)),
            ),
            new OA\Response(
                response: 404,
                description: 'Not Found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'message', type: 'string', example: 'Station with id "123" not found.',
                        ),
                    ],
                ),
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function getStationDetails(string $id, Request $request, WeatherStation $weatherStationClient): JsonResponse
    {
        $res = $weatherStationClient->fetchStation($id);

        $record = $res['result']['records'][0] ?? [];

        if (empty($record)) {
            return $this->json([
                'message' => sprintf('Station with id "%s" not found.', $id),
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json(
            new StationDetailsDTO(
                $record['_id'],
                $record['STATION_ID'],
                $record['NAME'],
                $record['WMO_ID'],
                $record['BEGIN_DATE'],
                $record['END_DATE'],
                $record['LATITUDE'],
                $record['LONGITUDE'],
                $record['GAUSS1'],
                $record['GAUSS2'],
                $record['GEOGR1'],
                $record['GEOGR2'],
                $record['ELEVATION'],
                $record['ELEVATION_PRESSURE'],
            ),
        );
    }
}
