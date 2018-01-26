<?php

namespace App\Auth\Social;

class GitHub extends Service
{

  public function getAuthorizeUrl()
  {
    return 'https://github.com/login/oauth/authorize?client_id=aedf310ffb19388de5d0&redirect_uri=http://localhost:8888/social-auth&scope=user,user:email&state=abc';
  }

  public function getUserByCode($code)
  {
    $token = $this->getAccessTokenFromCode($code);

    $user = $this->getUserByToken($token->access_token);

    return $this->normalizeUser($user);
  }

  public function getUserByToken($token)
  {
    $response = $this->client->request('GET', 'https://api.github.com/user', [
      'query' => [
        'access_token' => $token
      ]
    ])->getBody();

    return json_decode($response);
  }

  protected function getAccessTokenFromCode($code)
  {
    $response = $this->client->request('GET', 'https://github.com/login/oauth/access_token', [
      'query' => [
        'client_id' => '',
        'client_secret' => '',
        'redirect_uri' => 'http://localhost:8888/social-auth',
        'code' => $code,
        'state' => ''
      ],
      'headers' => [
        'accept' => 'application/json'
      ]
    ])->getBody();

    return json_decode($response);
  }

  protected function normalizeUser($user)
  {
    return (object) [
      'id' => $user->id,
      'name' => $user->name,
      'email' => $user->email,
      'photo' => $user->avatar_url
    ];
  }

}
