<?php namespace Tubecode\Contracts;


use Tubecode\Resources\ContentOwner;

interface FactoryInterface {

    /**
     * Create a new API object.
     *
     * @param ContentOwner $content_owner
     * @param              $service_account_name
     * @param              $key_file
     *
     * @return Tubecode\YouTube\Api
     */
    public static function create(ContentOwner $content_owner, $service_account_name, $key_file);
}