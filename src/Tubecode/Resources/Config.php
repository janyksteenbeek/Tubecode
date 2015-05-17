<?php namespace Tubecode\Resources;


class Config {


    /**
     * @var array
     */
    private $settings = [];

    public function __construct(array $settings)
    {

        $this->settings = $settings;
    }

    /**
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

}