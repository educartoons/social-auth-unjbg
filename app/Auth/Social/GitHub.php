<?php

namespace App\Auth\Social;

class Github extends Service
{
  public function getAuthorizeUrl()
  {  
    return 'https://github.com/login/oauth/authorize?client_id='.CLIENT_ID.'&redirect_uri=http://localhost:8888/social-auth-unjbg/&scope=user,user:email&state=abc';
  }

  public function getUserByCode($code)
  {

    $token = $this->getAccessTokenFromCode($code);

    $user = $this->getUserByToken($token->access_token);

    return $this->normalize($user);

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

  public function getAccessTokenFromCode($code)
  {
    $response = $this->client->request('GET', 'https://github.com/login/oauth/access_token', [
      'query' => [
        'client_id' => CLIENT_ID,
        'client_secret' => CLIENT_SECRET,
        'redirect_uri' => 'http://localhost:8888/social-auth-unjbg',
        'code' => $code,
        'state' => 'abc'
      ],
      'headers' =>[
        'accept' => 'application/json'
      ]
    ])->getBody();

    return json_decode($response);
  }

  public function normalize($user)
  {
    return (object) [
      'id' => $user->id,
      'name' => $user->name,
      'email' => $user->email,
      'photo' => $user->avatar_url
    ];
  }

}
