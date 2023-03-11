<?php 

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader 
{
    private $targetDirectory;

    public function __construct( $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }


    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();

        $file->move($this->targetDirectory, $fileName);

        return $fileName;
    }
}

