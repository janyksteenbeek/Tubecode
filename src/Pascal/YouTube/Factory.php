<?php namespace Pascal\YouTube;


use Google_Auth_AssertionCredentials;
use Google_Client;
use Google_Service_YouTube;
use Google_Service_YouTubePartner;
use Pascal\Contracts\FactoryInterface;
use Pascal\Resources\ContentOwner;

class Factory implements FactoryInterface {

    public static function create(ContentOwner $content_owner, $service_account_name, $key_file)
    {
        $client = new Google_Client();
        self::authorize_service_account($client, $service_account_name, $key_file);

        $youtube = new Google_Service_YouTube($client);
        $partner = new Google_Service_YouTubePartner($client);

        return new Api($client, $youtube, $partner, $content_owner);
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
}