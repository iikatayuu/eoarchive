<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Archive_service_model extends CI_Model {
  protected $table;
  protected $data_path;

  /**
   * Search pagination
   */
  public $q;
  public $offset;
  public $limit;

  /**
   * Adding/updating new executive order 
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

    $this->data_path = dirname(__FILE__, 6) . '/data';
    $this->table = json_decode(DB_TABLE);
    $this->load->model([]);
  }

  public function eos () {
    $this->db->select('*');
    $this->db->from($this->table->eo);
    $this->db->like('description', $this->q);
    $this->db->limit($this->limit, $this->offset);
    $result = $this->db->get()->result();

    $this->db->select('*');
    $this->db->from($this->table->eo);
    $this->db->like('description', $this->q);
    $count = $this->db->count_all_results();

    return array(
      'results' => $result,
      'count' => $count
    );
  }

  public function get_pdf () {
    $file_path = "{$this->data_path}/{$this->id}.pdf";
    return file_get_contents($file_path);
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
}
