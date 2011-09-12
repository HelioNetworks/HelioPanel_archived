<?php

namespace HelioNetworks\FileManagerBundle\Form\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadFileRequest extends FileRequest
{
    protected $uploadedFile;

    public function getUploadedFile()
    {
        return $this->uploadedFile;
    }

    public function setUploadedFile(UploadedFile $uploadedFile)
    {
        $this->uploadedFile = $uploadedFile;
    }
}
