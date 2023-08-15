<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admins_service_model extends CI_Model {
  protected $table;

  public $offset;
  public $limit;

  public $id;
  public $username;
  public $password;

  public function __construct () {
    parent::__construct();

    $this->load->model([]);
    $this->table = json_decode(DB_TABLE);
  }

  public function login () {
    $this->db->select('*');
    $this->db->from($this->table->admins);
    $this->db->where('username', $this->username);
    $this->db->limit(1);

    $result = $this->db->get()->result();
    $response = [
      'success' => false,
      'message' => ''
    ];
  
    if (count($result) > 0) {
      $admin = $result[0];
      if (password_verify($this->password, $admin->password)) {
        $response['success'] = true;
        $response['message'] = 'Logged in successfully!';
      } else {
        $response['message'] = 'Invalid password';
      }
    } else {
      $response['message'] = 'No admin found with that username';
    }

    return $response;
  }

  public function admins () {
    $this->db->select('*');
    $this->db->from($this->table->admins);
    $this->db->like('username', $this->username);

    $result = $this->db->get()->result();
    return array(
      'results' => array_slice($result, $this->offset, $this->limit),
      'count' => count($result)
    );
  }

  public function add_admin () {
    try {
      $this->db->trans_start();

      $data = array(
        'username' => $this->username,
        'password' => password_hash($this->password, PASSWORD_DEFAULT)
      );

      $this->db->insert($this->table->admins, $data);
      $insert_id = $this->db->insert_id();
      $this->db->trans_complete();

      if ($this->db->trans_status()) {
        $this->db->trans_commit();
        return array('success' => TRUE, 'message' => 'Added successfully!');
      } else {
        $this->db->trans_rollback();
        throw new Exception(ERROR_PROCESSING);
      }
    } catch (Exception $e) {
      return array('success' => FALSE, 'message' => $e->getMessage());
    }
  }

  public function edit_admin () {
    try {
      $this->db->trans_start();
      $data = array(
        'password' => password_hash($this->password, PASSWORD_DEFAULT)
      );

      $this->db->where('id', $this->id);
      $this->db->update($this->table->admins, $data);
      $this->db->trans_complete();
      if ($this->db->trans_status()) {
        $this->db->trans_commit();
        return array('success' => TRUE, 'message' => 'Updated successfully!');
      } else {
        $this->db->trans_rollback();
        throw new Exception(ERROR_PROCESSING);
      }
    } catch (Exception $e) {
      return array('success' => FALSE, 'message' => $e->getMessage());
    }
  }

  public function delete_admin () {
    try {
      $this->db->trans_start();
      $this->db->where('id', $this->id);
      $this->db->delete($this->table->admins);
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
}
