<?php
namespace App\Utils;

class FileUtil
{
    /**
     * @codeCoverageIgnore
     */
    public static function saveOnStorage($file, $storage) {
        $filename = time().rand(1000,9999).'.'.$file->extension();
        return $file->storeAs($storage, $filename);
    }

    /**
     * @codeCoverageIgnore
     */
    public static function removeBom($text)
    {
        $bom = pack('H*','EFBBBF');
        return preg_replace("/^$bom/", '', $text);
    }

    /**
     * @codeCoverageIgnore
     */
    public static function addBom($data) {
        return chr(239).chr(187).chr(191).$data;
    }
}