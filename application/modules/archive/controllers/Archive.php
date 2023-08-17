<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Archive extends MX_Controller {
  private $data;
  protected $baseurl;

  public function __construct () {
    parent::__construct();

    $this->baseurl = base_url();
    $this->load->library('session');
    $this->load->model([
      'archive/Archive_model' => 'aModel'
    ]);
  }

  public function index () {
    $this->load->view('index');
  }

  public function add () {
    $this->should_logged('/archive/add');
    $this->load->view('add');
  }

  public function view () {
    $this->aModel->id = $this->uri->segment(3);
    $this->data['eo'] = $this->aModel->get_eo();
    $this->load->view('view', $this->data);
  }

  public function edit () {
    $this->aModel->id = $this->uri->segment(3);
    $this->should_logged("/archive/edit/{$this->aModel->id}");

    $this->data['eo'] = $this->aModel->get_eo();
    $this->load->view('edit', $this->data);
  }

  private function should_logged ($next = '') {
    if (isset($this->session->username)) return;

    $url = urlencode($next);
    header("Location: {$this->baseurl}admins/login?next=$url");
    exit(0);
  }
}
