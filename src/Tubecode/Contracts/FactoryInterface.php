<?php

namespace Tubecode\Contracts;

use Tubecode\Collections\ContentOwners;

interface FactoryInterface
{

    /**
     * Initialize a new API class with a google service account.
     *
     * @param               $service_account_json_cred  Visit the Google Developer Console and
     *                                                  download the JSON object for your service account.
     * @param array         $scopes                     Additional scopes the service account should be authorized for.
     * @param null|string   $content_owner_id           The content owner you want all requests to be made against.
     *
     * @return ContentOwners|ContentOwnerInterface
     */
    public static function create($service_account_json_cred, $scopes = [], $content_owner_id = null);

    public static function createFromToken($access_key, $refresh_key = null);
}