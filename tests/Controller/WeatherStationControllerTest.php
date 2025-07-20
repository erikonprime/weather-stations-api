<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WeatherStationControllerTest extends WebTestCase
{
    private string $apiKey;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->apiKey = $_ENV['API_SECURITY_TOKEN'];
        $this->client = static::createClient();
    }

    public function testListStationsUnauthorized()
    {
        $this->client->request('GET', '/api/stations/list');
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
    }

    public function testListStationsAuthorized()
    {
        $this->client->request('GET', '/api/stations/list', [], [], [
            'HTTP_Authorization' => 'Bearer ' . $this->apiKey,
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testStationDetailsNotFound()
    {
        $this->client->request('GET', '/api/stations/FAKE/details', [], [], [
            'HTTP_Authorization' => 'Bearer ' . $this->apiKey,
        ]);
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    #[DataProvider('dataTestStationDetails')]
    public function testStationDetails(string $id, int $expectedCode)
    {
        $this->client->request('GET', sprintf('/api/stations/%s/details', $id), [], [], [
            'HTTP_Authorization' => 'Bearer ' . $this->apiKey,
        ]);
        $this->assertEquals($expectedCode, $this->client->getResponse()->getStatusCode());
    }

    public static function dataTestStationDetails(): array
    {
        return [
            ['RIME99MS', 200],
            ['FAKE', 404], //fake id
            ['RIDM99MS', 200],
        ];
    }
}
