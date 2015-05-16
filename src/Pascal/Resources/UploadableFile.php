<?php namespace Pascal\Resources;


class UploadableFile extends \SplFileInfo{

    public function __construct($file)
    {
        //TODO validate if correct video file
        parent::__construct($file);
    }

}