<?php

namespace Pivvenit\FactuurSturen\Util;

use Analog\Analog as Logger;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\ClientException;
use Pivvenit\FactuurSturen\Util\Settings;
use Pivvenit\FactuurSturen\Config;
use Pivvenit\FactuurSturen\Util\Backtrace;
use Pivvenit\FactuurSturen\Util\FactuursturenClient;
use Pivvenit\FactuurSturen\Util\FactuursturenInvoice;

class Factuursturen
{

    /**
     * Username to factuursturen.nl.
     *
     * @var string
     */
    protected $_user;

    /**
     * API Token to factuursturen.nl.
     *
     * @var string
     */
    protected $_token;

    /**
     * Settings section name.
     *
     * @var string
     */
    protected $_section;

    /**
     * Class constructor.
     *
     * @param string $user
     * @param string $token
     */
    public function __construct($user, $token, $section = null)
    {
        $this->_user = $user;
        $this->_token = $token;
        $this->_section = $section;
    }

    /**
     * Create instance based on credentials from settings section.
     *
     * @param string $section
     * @return $this
     */
    public static function createFromSettingsSection($section)
    {
        $called_class = get_called_class();
        return new $called_class(
            Settings::get_option(Settings::FIELD_FS_USER, $section),
            Settings::get_option(Settings::FIELD_FS_API_KEY, $section),
            $section
        );
    }

    /**
     * Get invoice util.
     *
     * @return \Pivvenit\FactuurSturen\Util\FactuursturenInvoice
     * @throws \InvalidArgumentException
     */
    public function getInvoiceUtil()
    {
        if ($this->_section) {
            return FactuursturenInvoice::createFromSettingsSection($this->_section);
        } else {
            throw new \InvalidArgumentException('Not able to generate invoice util, no section set.');
        }
    }

    /**
     * Get client util.
     *
     * @return \Pivvenit\FactuurSturen\Util\FactuursturenClient
     * @throws \InvalidArgumentException
     */
    public function getClientUtil()
    {
        if ($this->_section) {
            return FactuursturenClient::createFromSettingsSection($this->_section);
        } else {
            throw new \InvalidArgumentException('Not able to generate client util, no section set.');
        }
    }

    /**
     * Make an API request.
     *
     * @param string $method
     * @param string $uri
     * @param array $options
     */
    public function makeRequest($method, $uri, $options = array())
    {
        $config = Config::get_config();

        Logger::notice(Backtrace::getCallerClassMethod(debug_backtrace()) . ' - New API request, method: ' . $method . ', uri: ' . $config['factuursturen_endpoint'] . $uri . ', options:' . json_encode($options));

        try {
            $make = $this->getAuthorizedClient();
            $response = $make->request($method, $config['factuursturen_endpoint'] . $uri, $options);
        } catch(ClientException $e) {
            Logger::error('API ERROR response, status code: ' . $e->getResponse()->getStatusCode() . ', body: ' . $e->getResponse()->getBody()->getContents() . ', reason: ' . $e->getResponse()->getReasonPhrase());
        } catch(Exception $e) {
            Logger::error('API request FAILED with exception code: ' . $e->getCode() . ' and message: ' . $e->getMessage());
        }

		if ($response != null) {
			Logger::notice('API response, status code: ' . $response->getStatusCode() . ', body: ' . $response->getBody()->getContents() . ', reason: ' . $response->getReasonPhrase());
			$response->getBody()->rewind(); // reset body stream pointer
		}

        return $response;
    }

    /**
     * Get available languages in which the invoices can be generated (by factuursturen.nl).
     *
     * @return array
     */
    public static function getDoclangs()
    {
        $langs = array('nl', 'en', 'de', 'fr', 'es');
        return apply_filters('woocommerce_factuursturen_doclangs', $langs);
    }

    /**
     * Returns authorized client.
     *
     * @param string $settings_section
     * @return \GuzzleHttp\Client
     */
    protected function getAuthorizedClient()
    {
        return new GuzzleClient(array(
            RequestOptions::AUTH => array($this->_user, $this->_token),
            RequestOptions::HEADERS => array(
                'Accept' => 'application/json',
            ),
        ));
    }

}
