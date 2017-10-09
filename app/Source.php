<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

/**
 * Класс Source
 *
 * @package App
 *
 * @property string $id
 * @property string $name
 * @property string $base_url
 * @property string $api_path
 *
 * @property string $url
 * @property string $baseUrl
 * @property string $apiPath
 */
class Source extends Model
{
    protected $fillable = ['name', 'base_url', 'api_path'];

    protected $table = 'sources';


    public function getUrlAttribute()
    {
        return $this->baseUrl . $this->apiPath;
    }

    public function getBaseUrlAttribute()
    {
        return $this->attributes['base_url'];
    }

    public function setBaseUrlAttribute($baseUrl)
    {
        $this->attributes['base_url'] = $baseUrl;
    }

    public function getApiPathAttribute()
    {
        return $this->attributes['api_path'];
    }

    public function setApiPathAttribute($apiPath)
    {
        $this->attributes['api_path'] = $apiPath;
    }

    /**
     * @param array $clientConfig
     *
     * @return array
     */
    protected function beforeRequest($clientConfig)
    {
        return $clientConfig;
    }

    /**
     * @param Client $client
     * @param array $params
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    protected function sendRequest(Client $client, $params = [])
    {
        return $client->request('GET', $this->apiPath);
    }

    /**
     * @author  Марунин Алексей <a.marunin@globus-ltd.com>
     * @since
     *
     * @param object|array $data
     *
     * @return array
     */
    protected function afterRequest($data)
    {
    }

    /**
     * @param array $params
     *
     * @return int
     */
    public function request($params = [])
    {
        $clientConfig = [
            'base_uri' => $this->baseUrl,
            'timeout'  => 10,
        ];

        $clientConfig = $this->beforeRequest($clientConfig);
        $client = new Client($clientConfig);
        $response = $this->sendRequest($client, $params);
        if ($response->getStatusCode() == 200) {
            $data = json_decode($response->getBody(), true);
            $this->afterRequest($data);
        }

        return $response->getStatusCode();
    }

    protected static function sourceName()
    {
        return null;
    }
}
