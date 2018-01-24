<?php

namespace App\Auth\Social;

use GuzzleHttp\Client;

abstract class Service
{
  protected $client;

  public function __construct(Client $client){
    $this->client = $client;
  }

  abstract public function getAuthorizeUrl();
  abstract public function getUserByCode($code);

  public function authorizeUrl()
  {
    return $this->getAuthorizeUrl();
  }

  public function getUser($code)
  {
    return $this->getUserByCode($code);
  }

}
