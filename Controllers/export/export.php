<?php
  namespace App\Controllers\export;

  use App\Controllers\BaseController;

  use App\Models\customer\CustomerModel;

  use PhpOffice\PhpSpreadsheet\Spreadsheet;
  use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

  class Export extends BaseController {

      public function __construct() {
          $db                     = db_connect();
          $this->session          = \Config\Services::session();

          $this->customer         = new CustomerModel($db);

          $this->ip_address       = $_SERVER['REMOTE_ADDR'];
          $this->datetime         = date("Y-m-d H:i:s");

      }

      public function index() {
          $this->list();
      }

      public function list() {
          $data                   = [];
          $data ['content_title'] = 'Export to Excel';
          echo view('export/list', $data);
      }

      public function excel() {
          $alphabets  = [
          'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X','Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX','AY', 'AZ'
          ];
          $query      = '
          SELECT *
                  FROM customer_info
          ';
          $query_result   = $this->customer->runQuery($query);
          $list_fields = $query_result->getFieldNames();
          $result        = $query_result->getResult();
          if($result) {
              $spreadsheet     = new Spreadsheet();
              $worksheet      = $spreadsheet->getActiveSheet();
              foreach ($list_fields as $key => $field) {
                  $field_name = str_replace("_", " ", strtoupper($field));
                  $worksheet->setCellValue($alphabets[$key] . '1', $field_name);
              }

              $index         = 2;
              foreach ($result as $key => $row) {
                  $sub_index = 0;
                  foreach ($row as $sub_key => $value) {
                      $worksheet->setCellValue($alphabets[$sub_index] . $index, $value);
                      $sub_index++;
                  }
                  $index++;
              }

              $writer  = new Xlsx($spreadsheet);
              $fileName    = 'customer_info_'.date('dmYHis').'.xlsx';
              $writer->save($fileName);
              $json = [
              'message'        => showSuccessMessage("File has been exported successfully"),
                      'status'        => true,
                      'location'      => base_url($fileName),
   ];
          } else {
              $json = [
              'status'    => false,
                      'message'   => showDangerMessage("Entered email address does not exists. Please try again"),
          ];
          }
          echo json_encode($json);
      }
  }