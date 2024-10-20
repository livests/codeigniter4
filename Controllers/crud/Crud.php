<?php
namespace App\Controllers\crud;

use App\Controllers\BaseController;
use App\Models\state\StateModel;

class Crud extends BaseController {
    public function stateDropdown()
    {
        // Simulate getting the states from the database or another data source
        $states = [
            '1' => 'One',
            '2' => 'Two',
            '3' => 'Three',
        ];

        return $states;
    }

    public function __construct() {
        $db                 = db_connect();
        $this->state        = new StateModel($db);
    }

    public function index() {
        $this->find();
    }

    public function find() {
        $data                       = [];
        $data ['content_title']     = "Find State";
        $data ['state_dropdown']    = $this->stateDropdown();
        echo view('crud/find', $data);
    }

    public function search() {
        $search     = $this->request->getPost('search');
        $where      = 'name LIKE "%'.$search.'%"';
        $result     = $this->state->getEntryList($where);
        $list       = [];
        foreach($result as $row) {
            // $list [$row->name] = $row->name; 
            $list [] = [
            'id'    => $row->name,
                    'text'  => $row->name,                
        ];
        }
        echo json_encode($list);
    }

}