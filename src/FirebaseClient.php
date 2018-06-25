<?php

declare(strict_types = 1);

namespace McMatters\FirebaseApi;

use GuzzleHttp\Client;
use const null, true;
use function json_decode;

/**
 * Class FirebaseClient
 *
 * @package McMatters\FirebaseApi
 */
class FirebaseClient
{
    /**
     * @var Client
     */
    protected $httpClient;

    /**
     * FirebaseClient constructor.
     */
    public function __construct()
    {
        $this->httpClient = new Client();
    }

    /**
     * @param string $database
     * @param string $path
     * @param array $filters
     *
     * @return array|bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \RuntimeException
     */
    public function get(string $database, string $path, array $filters = [])
    {
        return $this->request($database, $path, 'GET', $filters);
    }

    /**
     * @param string $database
     * @param string $path
     * @param array $data
     * @param array $uriParameters
     *
     * @return array|bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \RuntimeException
     */
    public function save(string $database, string $path, array $data, array $uriParameters = [])
    {
        return $this->request($database, $path, 'PUT', $uriParameters, $data);
    }

    /**
     * @param string $database
     * @param string $path
     * @param array $data
     * @param array $uriParameters
     *
     * @return array|bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \RuntimeException
     */
    public function update(
        string $database,
        string $path,
        array $data,
        array $uriParameters = []
    ) {
        return $this->request($database, $path, 'PATCH', $uriParameters, $data);
    }

    /**
     * @param string $database
     * @param string $path
     * @param array $uriParameters
     *
     * @return array|bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \RuntimeException
     */
    public function delete(
        string $database,
        string $path,
        array $uriParameters = []
    ) {
        return $this->request($database, $path, 'DELETE', $uriParameters);
    }

    /**
     * @param string $database
     * @param string $path
     * @param string $method
     * @param array $query
     * @param array $data
     *
     * @return bool|array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \RuntimeException
     */
    protected function request(
        string $database,
        string $path,
        string $method,
        array $query = [],
        array $data = []
    ) {
        $response = $this->httpClient->request(
            $method,
            $this->getUrl($database, $path),
            ['query' => $query, 'json' => $data]
        );

        if ('silent' === ($query['print'] ?? null)) {
            return $response->getStatusCode() === 204;
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $database
     * @param string|null $path
     *
     * @return string
     */
    protected function getUrl(string $database, string $path): string
    {
        return "https://{$database}.firebaseio.com/{$path}.json";
    }
}
