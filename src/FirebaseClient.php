<?php

declare(strict_types=1);

namespace McMatters\FirebaseApi;

use McMatters\Ticl\Client;

use McMatters\Ticl\Enums\HttpStatusCode;

use const null;

/**
 * Class FirebaseClient
 *
 * @package McMatters\FirebaseApi
 */
class FirebaseClient
{
    /**
     * @var \McMatters\Ticl\Client
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $database;

    /**
     * @var string
     */
    protected $path;

    /**
     * FirebaseClient constructor.
     *
     * @param string|null $database
     * @param string|null $path
     */
    public function __construct(?string $database = null, ?string $path = null)
    {
        $this->httpClient = new Client();
        $this->database = $database;
        $this->path = $path;
    }

    /**
     * @param array $filters
     * @param string|null $database
     * @param string|null $path
     *
     * @return array|bool
     */
    public function get(
        array $filters = [],
        ?string $database = null,
        ?string $path = null
    ) {
        return $this->request('GET', $database, $path, $filters);
    }

    /**
     * @param array $data
     * @param array $uriParameters
     * @param string|null $database
     * @param string|null $path
     *
     * @return array|bool
     */
    public function save(
        array $data,
        array $uriParameters = [],
        ?string $database = null,
        ?string $path = null
    ) {
        return $this->request('PUT', $database, $path, $uriParameters, $data);
    }

    /**
     * @param array $data
     * @param array $uriParameters
     * @param string|null $database
     * @param string|null $path
     *
     * @return array|bool
     */
    public function update(
        array $data,
        array $uriParameters = [],
        ?string $database = null,
        ?string $path = null
    ) {
        return $this->request('PATCH', $database, $path, $uriParameters, $data);
    }

    /**
     * @param array $uriParameters
     * @param string|null $database
     * @param string|null $path
     *
     * @return array|bool
     */
    public function delete(
        array $uriParameters = [],
        ?string $database = null,
        ?string $path = null
    ) {
        return $this->request($database, $path, 'DELETE', $uriParameters);
    }

    /**
     * @param string $method
     * @param string|null $database
     * @param string|null $path
     * @param array $query
     * @param array $data
     *
     * @return bool|array
     */
    protected function request(
        string $method,
        ?string $database = null,
        ?string $path = null,
        array $query = [],
        array $data = []
    ) {
        /** @var \McMatters\Ticl\Http\Response $response */
        $response = $this->httpClient->{$method}(
            $this->getUrl($database, $path),
            ['query' => $query, 'json' => $data]
        );

        if ('silent' === ($query['print'] ?? null)) {
            return $response->getStatusCode() === HttpStatusCode::NO_CONTENT;
        }

        return $response->json();
    }

    /**
     * @param string $database
     * @param string $path
     *
     * @return string
     */
    protected function getUrl(
        ?string $database = null,
        ?string $path = null
    ): string {
        $database = $database ?? $this->database;
        $path = $path ?? $this->path;

        return "https://{$database}.firebaseio.com/{$path}.json";
    }
}
