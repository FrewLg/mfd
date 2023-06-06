<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private $targetDirectory;
    private $slugger;

    public function __construct($targetDirectory, SluggerInterface $slugger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file,$location=null)
    {
        // $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
       $fileName = uniqid().'.'.$file->guessExtension();

        try {

 
	//dd("public".$location?"/".$location:"");
           $file->move($this->getTargetDirectory().($location?"/".$location:""), $fileName);
        } catch (FileException $e) {
            dd($e);
         }

        return $fileName;
    }

    public function getTargetDirectory()
    {
 //return 'public/files/proposals';        
return $this->targetDirectory;
    }
}
