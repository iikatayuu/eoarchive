<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admins_service extends MX_Controller {
  public function __construct () {
    parent::__construct();

    $this->load->library('session');
    $this->load->model([
      'admins/service/Admins_service_model' => 'asModel'
    ]);
  }

  public function login () {
    $this->asModel->username = $this->input->post('username');
    $this->asModel->password = $this->input->post('password');

    $response = $this->asModel->login();
    if ($response['result']['success']) {
      $this->session->userid = $response['id'];
      $this->session->username = $this->asModel->username;
    }

    echo json_encode($response['result']);
  }

  public function admins () {
    $this->asModel->username = $this->input->get('username');
    $offset = $this->input->get('offset');
    $limit = $this->input->get('limit');
    $this->asModel->offset = is_numeric($offset) ? intval($offset) : 0;
    $this->asModel->limit = is_numeric($limit) ? intval($limit) : 10;

    $response = $this->asModel->admins();
    echo json_encode($response);
  }

  public function add_admin () {
    $this->should_logged();
    $this->asModel->username = $this->input->post('username');
    $this->asModel->password = $this->input->post('password');

    $response = $this->asModel->add_admin();
    echo json_encode($response);
  }

  public function edit_admin () {
    $this->should_logged();
    $this->asModel->id = $this->input->post('id');
    $this->asModel->password = $this->input->post('password');

    $response = $this->asModel->edit_admin();
    echo json_encode($response);
  }

  public function delete_admin () {
    $this->should_logged();
    $this->asModel->id = $this->input->post('id');

    $response = $this->asModel->delete_admin();
    echo json_encode($response);
  }

  private function should_logged () {
    if (isset($this->session->username)) return;

    echo json_encode([
      'success' => false,
      'message' => 'User is not logged in'
    ]);
    exit(0);
  }
}
