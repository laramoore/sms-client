<?php

namespace Matthewbdaly\SMS\Drivers;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Matthewbdaly\SMS\Contracts\Driver;

/**
 * Driver for RequestBin.
 */
class RequestBin implements Driver
{
    /**
     * Guzzle client.
     *
     * @var
     */
    protected $client;

    /**
     * Guzzle response.
     *
     * @var
     */
    protected $response;

    /**
     * Path.
     *
     * @var
     */
    private $path;

    /**
     * Endpoint.
     *
     * @var
     */
    private $endpoint = 'https://requestb.in/';

    /**
     * Constructor.
     *
     * @param GuzzleClient   $client   The Guzzle Client instance.
     * @param GuzzleResponse $response The Guzzle response instance.
     * @param array          $config   The configuration array.
     *
     * @return void
     */
    public function __construct(GuzzleClient $client, GuzzleResponse $response, array $config)
    {
        $this->client = $client;
        $this->response = $response;
        $this->path = $config['path'];
    }

    /**
     * Get driver name.
     *
     * @return string
     */
    public function getDriver(): string
    {
        return 'RequestBin';
    }

    /**
     * Get endpoint URL.
     *
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint.$this->path;
    }

    /**
     * Send the request.
     *
     * @param array $message An array containing the message.
     *
     * @throws \Matthewbdaly\SMS\Exceptions\ClientException  Client exception.
     * @throws \Matthewbdaly\SMS\Exceptions\ServerException  Server exception.
     * @throws \Matthewbdaly\SMS\Exceptions\NetworkException Network exception.
     * @throws \Matthewbdaly\SMS\Exceptions\ConnectException Connect exception.
     *
     * @return bool
     */
    public function sendRequest(array $message): bool
    {
        try {
            $response = $this->client->request('POST', $this->getEndpoint(), $message);
        } catch (ClientException $e) {
            throw new \Matthewbdaly\SMS\Exceptions\ClientException();
        } catch (ServerException $e) {
            throw new \Matthewbdaly\SMS\Exceptions\ServerException();
        } catch (ConnectException $e) {
            throw new \Matthewbdaly\SMS\Exceptions\ConnectException();
        } catch (RequestException $e) {
            throw new \Matthewbdaly\SMS\Exceptions\NetworkException();
        }

        return $response->getStatusCode() == 201;
    }
}
