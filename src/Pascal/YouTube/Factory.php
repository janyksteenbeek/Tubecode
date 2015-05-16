<?php namespace Pascal;


use Google_Client;

class YouTubeApiFactory {

    public static function create($service_account_name, $oauth_key_file)
    {
        $client = new Google_Client();
        self::authorize_service_account($client, $service_account_name, $oauth_key_file);

        $youtube = new Google_Service_YouTube($client);
        $partner = new Google_Service_YouTubePartner($client);
    }

    private static function authorize_service_account()
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