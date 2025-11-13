<?php

namespace App\Http\Controllers\Files;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class FileController
{
    public static function storeFile($file,$folder)
    {
        if (!$file) return null;


        $name =$file->hashName();


        Storage::disk('public')->putFileAs($folder, $file, $name);

        return $name;
    }

    public static function storeMultiple($files,$folder)
    {
        $StoredFiles=[];
        foreach ($files as $file)
        {
          $StoredFiles[]=  self::storeFile($file,$folder);
        }
        return $StoredFiles;

    }
    public static function updateMultiple($files, $oldFiles, $folder)
    {
        $newFiles=[];
        foreach ($files as $index => $file)
        {
            $oldFile = $oldFiles[$index] ?? null;
            $newFiles[]=self::updateFile($file, $oldFile, $folder);
        }
        return $newFiles;
    }
    public static function updateFile($file, $oldFile, $folder)
    {
        if($file==null){
            self::deleteFile($oldFile, $folder);
            return null;
        }

          if(self::compareFiles($oldFile,$file,$folder)){
              return $oldFile;
          }
        if ($file) {

            if ($oldFile) {
                self::deleteFile($oldFile, $folder);
            }

            return self::storeFile($file,$folder);
        }

        return $oldFile;
    }

    public static function deleteFile($file, $folder)
    {
        if ($file) {
            Storage::disk('public')->delete($folder . '/' . $file);
        }
    }
    public static function deleteAllFiles( $Files, $folder)
    {
        foreach ($Files as $file) {
        self::deleteFile($file, $folder);
        }
    }

    public static function compareFiles(?string $oldImageName,  $newFile, $folder): bool
    {
        if (!$oldImageName) return false;

        $oldPath = storage_path('app/public/' . $folder . '/' . $oldImageName);
        if (!file_exists($oldPath)) return false;

        return md5_file($oldPath) === md5_file($newFile->getRealPath());
    }
}
