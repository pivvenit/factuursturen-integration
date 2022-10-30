<?php

namespace Pivvenit\FactuurSturen\Util;

use Analog\Analog;
use GuzzleHttp\RequestOptions;
use Pivvenit\FactuurSturen\Util\Factuursturen;
use Pivvenit\FactuurSturen\RequestData\Invoice;

class FactuursturenInvoice extends Factuursturen
{

    /**
     * Creates the invoice.
     *
     * @param \WCFS\Module\Invoices\RequestData\Invoice $invoice
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function createInvoice(Invoice $invoice)
    {
        $response = $this->makeRequest('POST', 'invoices', array(RequestOptions::FORM_PARAMS => $invoice->toArray()));

        if ($response->getStatusCode() == 201) {
            $invoice->setId($response->getBody()->getContents());
            $response->getBody()->rewind(); // reset body stream pointer
        }

        return $response;
    }

    /**
     * Fetch all invoices.
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function fetchAll()
    {
        $response = $this->makeRequest('GET', 'invoices');

        return $response;
    }

    /**
     * Fetch the invoice.
     *
     * If the second argument passed and is a variable
     * it is changed into the populated invoice instance with data.
     *
     * @param int $id
     * @param \WCFS\Module\Invoices\RequestData\Invoice $invoice
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function fetchInvoice($id, &$invoice = null)
    {
        $response = $this->makeRequest('GET', 'invoices/' . $id);

        if (func_num_args() == 2) {
            $data = json_decode($response->getBody()->getContents(), true);
            $response->getBody()->rewind(); // reset body stream pointer

            if (isset($data['invoice']) && isset($data['invoice']['clientnr'])) {
                try {
                    $this->getClientUtil()->fetchClient($data['invoice']['clientnr'], $client);
                    $data['invoice']['client'] = $client;
                } catch(\Exception $e) {
                    Analog::error(sprintf('Could not fetch client, error message: %s.', $e->getMessage()));
                }
            }

            $invoice = isset($data['invoice']) ? Invoice::fromArray($data['invoice']) : null;
        }

        return $response;
    }

    /**
     * Delete the invoice.
     *
     * @param int $id
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function deleteInvoice($id)
    {
        return $this->makeRequest('DELETE', 'invoices/' . $id);
    }

    /**
     * Send the invoice data.
     *
     * @param \WCFS\Module\Invoices\InvoiceInterface $data
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function sendInvoice(InvoiceInterface $data)
    {
        return $this->makeRequest('POST', 'invoices', array(RequestOptions::FORM_PARAMS => array($data)));
    }

}
