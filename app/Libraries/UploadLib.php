<?php

namespace App\Libraries;

class UploadLib
{
    private $file;
    private $path;

    /**
     * Set the value of file
     *
     * @return  self
     */ 
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Set the value of path
     *
     * @return  self
     */ 
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    public function upload()
    {
        $th = date('Y/m/d');
        $_dir = $this->path . $th;
        $dir = UPLOADPATH . $this->path . $th;
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $newName = $this->file->getRandomName();;
        $this->file->move($dir, $newName);
        return $_dir.'/'.$newName;
    }

    public function rulesImage($variableName, $label)
    {
        $rules = [
            'label' => $label,
            'rules' => 'uploaded['.$variableName.']|is_image['.$variableName.']|mime_in['.$variableName.',image/jpg,image/jpeg,image/gif,image/png,image/webp]|max_size['.$variableName.',2048]',
            'errors' => [
                'max_size' => '{field} maksimal 2mb',
                'mime_in' => '{field} hanya upload file png, jpeg, jpg',
                'uploaded' => '{field} Tidak Sesuai',
                'is_image' => '{field} hanya upload file png, jpeg, jpg'
            ]
        ];
        return $rules;
    }

    public function rulesPdf($variableName, $label)
    {
        $rules = [
            'label' => $label,
            'rules' => 'uploaded['.$variableName.']|mime_in['.$variableName.',application/pdf]|max_size['.$variableName.',2048]',
            'errors' => [
                'max_size' => '{field} maksimal 2mb',
                'mime_in' => '{field} hanya upload pdf',
                'uploaded' => '{field} Tidak Sesuai',
            ]
        ];
        return $rules;
    }
}