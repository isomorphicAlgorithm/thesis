<?php

namespace App\Service\Client;

use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class AbstractClient
{
    public function __construct(protected HttpClientInterface $client) {}
}
