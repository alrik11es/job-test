<?php
namespace App\Logic\Helper;

class ImageHelper
{
    public $base_path = null;

    public function __construct()
    {
        $this->base_path = base_path() . '/storage/';
    }

    public function savePhoto($file)
    {
        $id = uniqid();
        if (is_null($file) || !$file->isValid()) {
            throw new \Exception('File could not be saved');
        }

        $hash = md5($id);

        $file_folder = '/photos/' . substr($hash, 0, 2) . '/' . substr($hash, 2, 2) . '/';

        if (!file_exists($this->base_path . $file_folder)) {
            mkdir($this->base_path . $file_folder, 0777, true);
        }
        $file_name = $hash . '.jpg';

        $file->move($this->base_path . $file_folder, $file_name);
        $img = \Image::make($this->base_path . $file_folder . $file_name);
        $img->fit(1920, 1080);
        $img->save($this->base_path . $file_folder . $file_name);

        return $file_name;
    }

}