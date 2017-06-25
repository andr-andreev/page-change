<?php

declare(strict_types=1);

namespace app\components\pagechange;

use app\components\pagechange\responses\ContentResponse;
use app\components\pagechange\responses\ExceptionResponse;
use app\components\pagechange\responses\ResponseInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;

/**
 * Class HttpClient
 * @package app\components\pagechange
 */
class HttpClient
{
    /**
     * @var Client
     */
    protected $client;
    /**
     * @var string
     */
    protected $url;

    /**
     * HttpClient constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->client = new Client([
            'timeout' => \Yii::$app->params['timeout']
        ]);
        $this->url = $url;
    }

    /**
     * @return ResponseInterface
     */
    public function getContent(): ResponseInterface
    {
        try {
            $response = $this->client->request('GET', $this->url, [
                'headers' => ['User-Agent' => 'Mozilla/5.0']
            ]);

            return new ContentResponse($response);
        } catch (TransferException $e) {
            return new ExceptionResponse($e);
        }
    }
}
