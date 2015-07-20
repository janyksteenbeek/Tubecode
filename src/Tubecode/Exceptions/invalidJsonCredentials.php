<?php namespace Tubecode\Exceptions;

class invalidJsonCredentialsType extends \Exception {

    protected $message = 'The provided json credentials object has an invalid type';
}