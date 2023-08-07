<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Archive extends MX_Controller {
  private $data;

  public function __construct () {
    parent::__construct();

    $this->load->model([
      'archive/Archive_model' => 'aModel'
    ]);
  }

  public function index () {
    $this->load->view('index');
  }

  public function add () {
    $this->load->view('add');
  }

  public function view () {
    $this->aModel->id = $this->uri->segment(3);
    $this->data['eo'] = $this->aModel->get_eo();
    $this->load->view('view', $this->data);
  }

  public function edit () {
    $this->aModel->id = $this->uri->segment(3);
    $this->data['eo'] = $this->aModel->get_eo();
    $this->load->view('edit', $this->data);
  }
}
