<?php

namespace App\Auth\Social;

class Facebook extends Service
{
  public function getAuthorizeUrl()
  {
    return 'https://www.facebook.com/v2.11/dialog/oauth?client_id=200140447391230&redirect_uri=http://localhost:8888/social-auth/&scope=email,public_profile&state=abc';
  }

  public function getUserByCode($code)
  {
    $token = $this->getAccessTokenFromCode($code);

    $user = $this->getUserByToken($token->access_token);

    // var_dump($user);
    //
    // die();

    return $this->normalizeUser($user);

  }

  public function getAccessTokenFromCode($code)
  {
    $response = $this->client->request('GET', 'https://graph.facebook.com/v2.3/oauth/access_token', [
      'query' => [
        'client_id' => '',
        'client_secret' => '',
        'redirect_uri' => 'http://localhost:8888/social-auth/',
        'code' => $code
      ]
    ])->getBody();

    return json_decode($response);
  }

  public function getUserByToken($token)
  {
    $response = $this->client->request('GET', 'https://graph.facebook.com/me', [
      'query' => [
        'access_token' => $token,
        'fields' => 'id, name, email, picture'
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
      'photo' => $user->picture->data->url
    ];
  }

}
