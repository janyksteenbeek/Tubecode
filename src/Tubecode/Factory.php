<?php namespace Tubecode\YouTube;


use Google_Auth_AssertionCredentials;

use Tubecode\Contracts\FactoryInterface;

class Factory implements FactoryInterface {

    public static function create($service_account_name, $key_file, $content_owner_id = null)
    {

    }

    public static function createFromToken($access_key, $refresh_key = null)
    {

    }

    private static function authorize_service_account($client, $service_account_name, $key_file)
    {
        $key = file_get_contents($key_file);
        $cred = new Google_Auth_AssertionCredentials(
            $service_account_name,
            ['https://www.googleapis.com/auth/youtubepartner'],
            $key);
        $client->setAssertionCredentials($cred);

        if($client->getAuth()->isAccessTokenExpired())
        {
            $client->getAuth()->refreshTokenWithAssertion($cred);
        }
    }


    /*
     * Account::createFromToken('access_token', 'refresh_token') //Is a YouTube Channel
     * Account::create('service_account_name', 'key_file') //Is a Content Owner
     */
}