<?php

namespace Pivvenit\FactuurSturen\Util;

use GuzzleHttp\RequestOptions;
use Pivvenit\FactuurSturen\Util\Factuursturen;
use Pivvenit\FactuurSturen\RequestData\Client;

class FactuursturenClient extends Factuursturen
{

    /**
     * Creates the client.
     *
     * @param \Pivvenit\FactuurSturen\RequestData\Client $client
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function createClient(Client $client)
    {
        $response = $this->makeRequest('POST', 'clients', array(RequestOptions::FORM_PARAMS => $client->toArray()));

        if ($response->getStatusCode() == 201) {
            $client->setId($response->getBody()->getContents());
            $response->getBody()->rewind(); // reset body stream pointer
        }

        return $response;
    }

    /**
     * Fetch the client.
     *
     * If the second argument passed and is a variable
     * it is changed into the populated client instance with data.
     *
     * @param int $id
     * @param null $client
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function fetchClient($id, &$client = null)
    {
        $response = $this->makeRequest('GET', 'clients/' . $id);

        if (func_num_args() == 2) {
            $data = json_decode($response->getBody()->getContents(), true);
            $response->getBody()->rewind(); // reset body stream pointer
            $client = isset($data['client']) ? Client::fromArray($data['client']) : null;
        }

        return $response;
    }

    /**
     * Delete the client.
     *
     * @param int $id
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function deleteClient($id)
    {
        return $this->makeRequest('DELETE', 'clients/' . $id);
    }

}
