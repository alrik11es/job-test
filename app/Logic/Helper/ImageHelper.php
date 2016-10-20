<?php
namespace App\Logic\Helper;

class ImageHelper
{
    public $base_path = null;

    public function __construct()
    {
        $this->base_path = base_path() . '/storage/photos/';
    }

    /**
     * @param $file \Illuminate\Http\UploadedFile
     * @return string
     * @throws \Exception
     */
    public function savePhoto($file)
    {
        $id = uniqid();
        if (is_null($file) || !$file->isValid()) {
            throw new \Exception('File could not be saved');
        }

        $hash = md5($id);

        $file_folder = substr($hash, 0, 2) . '/' . substr($hash, 2, 2) . '/';

        $this->createFolderIfNotExists($this->base_path.$file_folder);
        $this->createFolderIfNotExists($this->base_path.'mini/');
        $this->createFolderIfNotExists($this->base_path.'mini/'.$file_folder);

        $file_name = $hash . '.jpg';

        /** @var \Intervention\Image\Image $img */
        $img = \Image::make($file->getPath().DIRECTORY_SEPARATOR.$file->getFilename());

        if($img->width()>1980 || $img->height()>1080){
            throw new \Exception('Image wider than supported format 1920x1080.');
        }

        $file->move($this->base_path . $file_folder, $file_name);

        $img->fit(600, 400);
        $img->save($this->base_path.'/mini/'.$file_folder.DIRECTORY_SEPARATOR.$file_name);

        return $file_folder . $file_name;
    }

    /**
     * @param $file_folder
     */
    public function createFolderIfNotExists($file_folder)
    {
        if (!file_exists($file_folder)) {
            mkdir($file_folder, 0777, true);
        }
    }

}