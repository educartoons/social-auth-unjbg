<?php

namespace App\Auth\Social;

class Facebook extends Service
{
  public function getAuthorizeUrl()
  {
    return 'Facebook';
  }

  public function getUserByCode($code)
  {

  }
}
