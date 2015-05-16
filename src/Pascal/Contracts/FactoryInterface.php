<?php namespace Pascal\Contracts;


use Pascal\Resources\ContentOwner;

interface FactoryInterface {

    /**
     * Create a new API object.
     *
     * @param ContentOwner $content_owner
     * @param              $service_account_name
     * @param              $key_file
     *
     * @return Pascal\YouTube\Api
     */
    public static function create(ContentOwner $content_owner, $service_account_name, $key_file);
}