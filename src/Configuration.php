<?php

namespace Hellovoid\Gdax;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;

class Configuration
{
    const DEFAULT_API_URL = 'https://api.pro.coinbase.com';
    const SANDBOX_API_URL = 'https://api-public.sandbox.pro.coinbase.com';

    private $authentication;
    private $apiUrl;

    /**
     * Creates a new configuration with API key authentication.
     *
     * @param string $apiKey    An API key
     * @param string $apiSecret An API secret
     * @param string $apiPassphrase An API passphrase
     * @param bool $isSandbox is API working in sandbox mode
     *
     * @return Configuration A new configuration instance
     */
    public static function apiKey($apiKey, $apiSecret, $apiPassphrase, $isSandbox)
    {        
        return new static(
            new Authentication($apiKey, $apiSecret, $apiPassphrase), 
            $isSandbox
        );
    }

    public function __construct(Authentication $authentication, $isSandbox)
    {        
        $this->authentication = $authentication;
        
        if ($isSandbox) {
            $this->apiUrl = self::SANDBOX_API_URL;
        }
        else {
            $this->apiUrl = self::DEFAULT_API_URL;
        }
    }

    /**
     * @param ClientInterface|null $transport
     * @return HttpClient
     */
    public function createHttpClient(ClientInterface $transport = null)
    {
        $httpClient = new HttpClient(
            $this->apiUrl,
            $this->authentication,
            $transport ?: new GuzzleClient()
        );

        return $httpClient;
    }

    public function getAuthentication()
    {
        return $this->authentication;
    }

    public function setAuthentication(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    public function setApiUrl($apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }
}
