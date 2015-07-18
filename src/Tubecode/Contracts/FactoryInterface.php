<?php namespace Tubecode\Contracts;

interface FactoryInterface {

    public static function create($service_account_name, $key_file,$content_owner_id = null);

    public static function createFromToken($access_key, $refresh_key = null);
}