<?php

namespace Tubecode;

use Google_Auth_AssertionCredentials;
use Google_Client;
use Tubecode\Collections\ContentOwners;
use Tubecode\Contracts\FactoryInterface;
use Tubecode\Exceptions\invalidJsonCredentialsType;

class Factory implements FactoryInterface
{
    private static $SERVICE_ACCOUNT_TYPE = "service_account";

    public static function create($service_account_json_cred, $scopes = [], $content_owner_id = null)
    {
        $client = new Google_Client();

        $client = self::authorize_service_account($service_account_json_cred, $client, $scopes);

        return ContentOwners::create($client, $content_owner_id);
    }

    public static function createFromToken($access_key, $refresh_key = null)
    {

    }

    private static function authorize_service_account($service_account_json_cred, Google_Client $client, $scopes = [])
    {
        $service_account_cred = json_decode($service_account_json_cred, true);

        if($service_account_cred['type'] != self::$SERVICE_ACCOUNT_TYPE)
        {
            throw new invalidJsonCredentialsType();
        }

        $scopes = array_merge($scopes, ['https://www.googleapis.com/auth/youtubepartner']);

        $cred = new Google_Auth_AssertionCredentials(
            $service_account_cred['client_email'],
            $scopes,
            $service_account_cred['private_key']);

        $client->setAssertionCredentials($cred);

        if($client->getAuth()->isAccessTokenExpired())
        {
            $client->getAuth()->refreshTokenWithAssertion($cred);
        }

        return $client;
    }
}