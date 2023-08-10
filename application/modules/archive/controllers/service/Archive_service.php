<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Archive_service extends MX_Controller {
  public function __construct () {
    parent::__construct();

    $this->load->model([
      'archive/service/Archive_service_model' => 'asModel'
    ]);
  }

  public function eos () {
    $this->asModel->description = $this->input->get('description');
    $this->asModel->series = $this->input->get('series');
    $this->asModel->author = $this->input->get('author');
    $this->asModel->approved_by = $this->input->get('approved_by');
    $this->asModel->approved_from = $this->input->get('approved_from');
    $this->asModel->approved_to = $this->input->get('approved_to');
    $offset = $this->input->get('offset');
    $limit = $this->input->get('limit');
    $this->asModel->offset = is_numeric($offset) ? intval($offset) : 0;
    $this->asModel->limit = is_numeric($limit) ? intval($limit) : 10;

    $response = $this->asModel->eos();
    echo json_encode($response);
  }

  public function get_pdf () {
    $this->asModel->id = $this->uri->segment(5);
    $response = $this->asModel->get_pdf();
    $orig_name = $response['filename'];

    header('Content-Type: application/pdf');
    header("Content-Disposition: inline; filename=$orig_name");
    echo $response['pdf'];
  }

  public function add_eo () {
    $this->asModel->number = $this->input->post('eo-num');
    $this->asModel->series = $this->input->post('eo-series');
    $this->asModel->description = $this->input->post('eo-description');
    $this->asModel->author = $this->input->post('eo-author');
    $this->asModel->author_position = $this->input->post('eo-author-position');
    $this->asModel->approved_by = $this->input->post('eo-approved');
    $this->asModel->approved_date = $this->input->post('eo-approved-date');
    $this->asModel->file = isset($_FILES['eo-file']) ? $_FILES['eo-file'] : null;

    $response = $this->asModel->add_eo();
    echo json_encode($response);
  }

  public function edit_eo () {
    $this->asModel->id = $this->input->post('eo-id');
    $this->asModel->number = $this->input->post('eo-num');
    $this->asModel->series = $this->input->post('eo-series');
    $this->asModel->description = $this->input->post('eo-description');
    $this->asModel->author = $this->input->post('eo-author');
    $this->asModel->author_position = $this->input->post('eo-author-position');
    $this->asModel->approved_by = $this->input->post('eo-approved');
    $this->asModel->approved_date = $this->input->post('eo-approved-date');
    $this->asModel->file = isset($_FILES['eo-file']) ? $_FILES['eo-file'] : null;

    $response = $this->asModel->edit_eo();
    echo json_encode($response);
  }

  public function delete_eo () {
    $this->asModel->id = $this->input->post('id');

    $response = $this->asModel->delete_eo();
    echo json_encode($response);
  }
}
