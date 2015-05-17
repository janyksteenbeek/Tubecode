<?php namespace Pascal\Resources\Config;

use Pascal\Resources\Config;

class VideoUploadConfig extends Config {

    public function getChunkSizeBytes()
    {
        if(array_key_exists('chunkSizeBytes', $this->getSettings())) {
            return $this->getSettings()['chunkSizeBytes'];
        }

        return null;
    }
}