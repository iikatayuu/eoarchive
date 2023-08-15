<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class Archive_service_model extends CI_Model {
  protected $table;
  protected $data_path;
  protected $alpha;

  /**
   * Search pagination
   */
  public $offset;
  public $limit;

  public $approved_from;
  public $approved_to;

  /**
   * Searching, adding, and updating new executive order 
   */
  public $number;
  public $series;
  public $description;
  public $author;
  public $author_position;
  public $approved_by;
  public $approved_date;
  public $file;

  /**
   * Updating/deleting executive order
   */
  public $id;

  public function __construct () {
    parent::__construct();

    $this->alpha = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $this->data_path = dirname(__FILE__, 6) . '/data';
    $this->load->model([]);
    $this->table = json_decode(DB_TABLE);
  }

  public function eos () {
    $this->db->select('*');
    $this->db->from($this->table->eo);
    $this->db->like('description', $this->description);
    $this->db->like('series', $this->series);
    $this->db->like('author', $this->author);
    $this->db->like('approved_by', $this->approved_by);

    if ($this->approved_from && $this->approved_to) {
      $approved_from = date('Y-m-d H:i:s', strtotime($this->approved_from));
      $approved_to = date('Y-m-d H:i:s', strtotime($this->approved_to));
      $this->db->where("date_approved BETWEEN '$approved_from' AND '$approved_to'");
    } else if ($this->approved_from) {
      $approved_from = date('Y-m-d H:i:s', strtotime($this->approved_from));
      $this->db->where("date_approved > '$approved_from'");
    } else if ($this->approved_to) {
      $approved_to = date('Y-m-d H:i:s', strtotime($this->approved_to));
      $this->db->where("date_approved < '$approved_to'");
    }

    $result = $this->db->get()->result();
    return array(
      'results' => array_slice($result, $this->offset, $this->limit),
      'count' => count($result)
    );
  }

  public function export_excel () {
    $spreadsheet = $this->export();

    $writer = new Xlsx($spreadsheet);
    $filename = strval(time());

    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=\"$filename.xlsx\"");
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
  }

  public function export_pdf () {
    $spreadsheet = $this->export();

    $writer = new Mpdf($spreadsheet);
    $filename = strval(time());

    header('Content-Type: application/pdf');
    header("Content-Disposition: attachment; filename=\"$filename.pdf\"");
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
  }

  public function get_pdf () {
    $this->db->select('*');
    $this->db->from($this->table->eo);
    $this->db->where('id', $this->id);
    $this->db->limit(1);
    $result = $this->db->get()->result();
    $filename = count($result) > 0 ? $result[0]->filename : $this->id;

    return [
      'filename' => $filename,
      'pdf' => file_get_contents("{$this->data_path}/{$this->id}.pdf")
    ];
  }

  public function add_eo () {
    try {
      $this->check_data();
      $this->db->trans_start();
      
      $approved_timestamp = strtotime($this->approved_date);
      $data = array(
        'number' => $this->number,
        'series' => $this->series,
        'description' => $this->description,
        'filename' => $this->file['name'],
        'author' => $this->author,
        'author_position' => $this->author_position,
        'approved_by' => $this->approved_by,
        'date_approved' => date('Y-m-d H:i:s', $approved_timestamp)
      );

      $this->db->insert($this->table->eo, $data);
      $insert_id = $this->db->insert_id();
      $this->db->trans_complete();

      if ($this->db->trans_status()) {
        $this->db->trans_commit();
        $file_path = "{$this->data_path}/$insert_id.pdf";
        move_uploaded_file($this->file['tmp_name'], $file_path);

        return array('success' => TRUE, 'message' => 'Added successfully!');
      } else {
        $this->db->trans_rollback();
        throw new Exception(ERROR_PROCESSING);
      }
    } catch (Exception $e) {
      return array('success' => FALSE, 'message' => $e->getMessage());
    }
  }

  public function edit_eo () {
    try {
      $this->check_data(false);
      if (empty($this->id)) {
        throw new Exception(INVALID_INPUT);
      }

      $this->db->trans_start();
      $update_file = !empty($this->file);
      $approved_timestamp = strtotime($this->approved_date);
      $data = array(
        'number' => $this->number,
        'series' => $this->series,
        'description' => $this->description,
        'author' => $this->author,
        'author_position' => $this->author_position,
        'approved_by' => $this->approved_by,
        'date_approved' => date('Y-m-d H:i:s', $approved_timestamp)
      );

      if ($update_file) $data['filename'] = $this->file['name'];

      $this->db->where('id', $this->id);
      $this->db->update($this->table->eo, $data);
      $this->db->trans_complete();
      if ($this->db->trans_status()) {
        $this->db->trans_commit();

        if ($update_file) {
          $file_path = "{$this->data_path}/{$this->id}.pdf";
          unlink($file_path);
          move_uploaded_file($this->file['tmp_name'], $file_path);
        }

        return array('success' => TRUE, 'message' => 'Updated successfully!');
      } else {
        $this->db->trans_rollback();
        throw new Exception(ERROR_PROCESSING);
      }
    } catch (Exception $e) {
      return array('success' => FALSE, 'message' => $e->getMessage());
    }
  }

  public function delete_eo () {
    try {
      $this->db->trans_start();
      $this->db->where('id', $this->id);
      $this->db->delete($this->table->eo);
      $this->db->trans_complete();
      if ($this->db->trans_status()) {
        $this->db->trans_commit();
        return array('success' => TRUE, 'message' => 'Deleted successfully!');
      } else {
        $this->db->trans_rollback();
        throw new Exception(ERROR_PROCESSING);
      }
    } catch (Exception $e) {
      return array('success' => FALSE, 'message' => $e->getMessage());
    }
  }

  private function check_data ($check_file = true) {
    if (
      empty($this->number) ||
      !is_numeric($this->number) ||
      empty($this->series) ||
      empty($this->description) ||
      empty($this->author) ||
      empty($this->author_position) ||
      empty($this->approved_by) ||
      empty($this->approved_date) ||
      (
        $check_file && (
          empty($this->file) ||
          !file_exists($this->file['tmp_name']) ||
          !is_uploaded_file($this->file['tmp_name']) ||
          mime_content_type($this->file['tmp_name']) !== 'application/pdf'
        )
      )
    ) {
      throw new Exception(INVALID_INPUT);
    }
  }

  private function export () {
    $result_arr = $this->eos();
    $spreadsheet = new Spreadsheet();
    $properties = $spreadsheet->getProperties();
    $sheet = $spreadsheet->getActiveSheet();

    $headers = ['EO Num', 'Series', 'Description', 'Author', 'Author Position', 'Approved By', 'Date Approved'];
    $headers_len = count($headers);
    foreach ($headers as $i => $header) {
      $col = substr($this->alpha, $i, 1);
      $sheet->setCellValue("{$col}1", $header);
    }

    for ($i = 0; $i < $result_arr['count']; $i++) {
      $result = $result_arr['results'][$i];
      $row = $i + 2;

      for ($j = 0; $j < $headers_len; $j++) {
        $header = $headers[$j];
        $col = substr($this->alpha, $j, 1);
        $value = '';

        if ($header === 'EO Num') $value = $result->number;
        if ($header === 'Series') $value = $result->series;
        if ($header === 'Description') $value = $result->description;
        if ($header === 'Author') $value = $result->author;
        if ($header === 'Author Position') $value = $result->author_position;
        if ($header === 'Approved By') $value = $result->approved_by;
        if ($header === 'Date Approved') $value = $result->date_approved;

        $sheet->setCellValue("{$col}{$row}", $value);
      }
    }

    $end_col = substr($this->alpha, $headers_len - 1, 1);
    $end_row = $result_arr['count'] + 1;
    $end_cell = "$end_col$end_row";
    $style_arr = [
      'borders' => [
        'allBorders' => [
          'borderStyle' => Border::BORDER_THIN,
          'color' => ['rgb' => '000000']
        ]
      ]
    ];

    $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
    $sheet->getStyle("A1:$end_cell")->applyFromArray($style_arr);
    $sheet->getColumnDimension('A')->setWidth(58, 'px');
    $sheet->getColumnDimension('B')->setWidth(80, 'px');
    $sheet->getColumnDimension('C')->setWidth(450, 'px');
    $sheet->getColumnDimension('D')->setWidth(160, 'px');
    $sheet->getColumnDimension('E')->setWidth(125, 'px');
    $sheet->getColumnDimension('F')->setWidth(160, 'px');
    $sheet->getColumnDimension('G')->setWidth(125, 'px');

    $time = time();
    $title = "EOArchive Export {$time}";
    $properties->setCreator('EOArchive');
    $properties->setTitle($title);
    $properties->setSubject($title);

    return $spreadsheet;
  }
}
