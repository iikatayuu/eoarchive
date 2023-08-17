<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admins extends MX_Controller {
  private $data;
  protected $baseurl;

  public function __construct () {
    parent::__construct();

    $this->baseurl = base_url();
    $this->load->library('session');
    $this->load->model([
      'admins/Admins_model' => 'aModel'
    ]);
  }

  public function index () {
    $this->should_logged("{$this->baseurl}admins");
    $this->load->view('index');
  }

  public function login () {
    $this->load->view('login');
  }

  public function logout () {
    $this->session->sess_destroy();
    header("Location: {$this->baseurl}admins/login");
  }

  private function should_logged ($next = '') {
    if (isset($this->session->username)) return;

    $url = urlencode($next);
    header("Location: {$this->baseurl}admins/login?next=$url");
    exit(0);
  }
}
