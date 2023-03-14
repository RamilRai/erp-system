<?php
namespace App\Traits;

/**
 * 
 */
trait ImageProcessTrait
{
    public static function uploadImage($image, $folder, $fileName)
    {
        $uploadFile = public_path(($folder), $fileName);

        if (!\File::exists( $uploadFile)) {
            \File::makeDirectory($uploadFile, 0755, true);
        }

        $processFile = \Image::make($image->getRealPath());
        $getFileSize = $image->getSize();
        if ($getFileSize > 1000000) {
            $processFile->save($uploadFile."/".$fileName,40,"jpg");
        } else {
            $processFile->save($uploadFile."/".$fileName);
        }
    }
}

?>