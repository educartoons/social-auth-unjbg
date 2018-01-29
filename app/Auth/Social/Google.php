<?php

namespace App\Auth\Social;

use GuzzleHttp\Exception\BadResponseException;

class Google extends Service
{
    public function getAuthorizeUrl()
    {
        return 'https://accounts.google.com/o/oauth2/v2/auth?scope=email%20profile&response_type=code&state=security_token%3D138r5719ru3e1%26url%3Dhttps://oauth2.example.com/token&redirect_uri=' . REDIRECT_URL_CALLBACK . '&client_id=' . CLIENT_ID;
    }

    public function getUserByCode($code)
    {
        $token = $this->getAccessTokenFromCode($code);
        $user = $this->getUserByToken($token->access_token);
        return $this->normalizeUser($user);
    }

    public function getAccessTokenFromCode($code)
    {
        $response = $this->client->request('POST', 'https://www.googleapis.com/oauth2/v4/token', [
            'query' => [
                'code' => $code,
                'client_id' => CLIENT_ID,
                'client_secret' => CLIENT_SECRET_KEY,
                'redirect_uri' => REDIRECT_URL_CALLBACK,
                'grant_type' => 'authorization_code'
            ]
        ])->getBody();
        return json_decode($response);
    }

    protected function getUserByToken($token)
    {
        // Consume the https://www.googleapis.com/oauth2/v1/tokeninfo url with GET method and access_token param to see access token information
        try {
            $response = $this->client->request('GET', 'https://www.googleapis.com/plus/v1/people/me', [

                'query' => [
                    'access_token' => $token
                ]
            ])->getBody();
            return json_decode($response);
        } catch (BadResponseException $ex) {
            $response = $ex->getResponse()->getBody();
            return json_decode($response);
        }
    }

    protected function normalizeUser($user)
    {
        return (object)[
            'id' => $user->id,
            'name' => $user->displayName,
            'email' => $user->emails[0]->value,
            'photo' => $user->image->url
        ];
    }
}