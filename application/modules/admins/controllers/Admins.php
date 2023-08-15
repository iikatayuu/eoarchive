<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admins extends MX_Controller {
  private $data;

  public function __construct () {
    parent::__construct();

    $this->load->library('session');
    $this->load->model([
      'admins/Admins_model' => 'aModel'
    ]);
  }

  public function index () {
    $this->should_logged('/admins');
    $this->load->view('index');
  }

  public function login () {
    $this->load->view('login');
  }

  public function logout () {
    $this->session->sess_destroy();
    header('Location: /admins/login');
  }

  private function should_logged ($next = '') {
    if (isset($this->session->username)) return;

    $url = urlencode($next);
    header("Location: /admins/login?next=$url");
    exit(0);
  }
}
