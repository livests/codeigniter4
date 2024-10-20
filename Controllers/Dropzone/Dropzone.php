<?php
namespace App\Controllers\Dropzone;

use App\Controllers\BaseController;

class Dropzone extends BaseController {
    public function __construct() {
        // Load any necessary libraries or models here
    }

    public function index() {
        $data = [
            'content_title' => "File Upload using Dropzone Example",
            'main_content' => "dropzone/dropzone"
        ];
        echo view('innerpages/template', $data);
    }

    public function upload() {
        $file = $this->request->getFile('file');
        if ($file->isValid()) {
            $path = 'uploads/media/' . date('Y') . '/' . date('m') . '/';
            $file_data = $this->uploadMediaFile($path, $file);

            if ($file_data) {
                $json = [
                    'message' => "File has been uploaded successfully.",
                    'status' => true,
                ];
            } else {
                $json = [
                    'message' => "There is an issue while uploading the file.",
                    'status' => false,
                ];
            }
        } else {
            $json = [
                'message' => "Please select a valid file",
                'status' => false,
            ];
        }

        return $this->response->setJSON($json);
    }

    private function uploadMediaFile($path, $image) {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        if ($image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move($path, $newName);
            return $path . $newName;
        }
        return false;
    }
}