<?php
namespace App\Controllers\autocomplete;

use App\Controllers\BaseController;
use App\Models\state\StateModel;

class Autocomplete extends BaseController {

    public function __construct() {
        $db                 = db_connect();
        $this->state        = new StateModel($db);
    }

    public function index() {
        $this->find();
    }

    public function find() {
        $data                       = [];
        $data ['content_title']     = "Autocomplete Example";
        $data ['main_content']      = "autocomplete/find";
        echo view('innerpages/template', $data);
    }

    public function search() {
        $keyword    = $this->request->getPost('keyword');
        $where      = 'name LIKE "%'.$keyword.'%"';
        $result     = $this->state->getEntryList($where);
        $list = [];
        foreach($result as $row) {
            $list [] = [
            'id'    => $row->id,
                    'value' => $row->name,
        ];
        }
        echo json_encode($list);
    }
}