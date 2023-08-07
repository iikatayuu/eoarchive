<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Archive_model extends CI_Model {
  protected $table;
  public $id;

  public function __construct () {
    parent::__construct();

    $this->load->model([]);
    $this->table = json_decode(DB_TABLE);
  }

  public function get_eo () {
    $this->db->select('*');
    $this->db->from($this->table->eo);
    $this->db->where('id', $this->id);
    $this->db->limit(1);
    return $this->db->get()->row();
  }
}
