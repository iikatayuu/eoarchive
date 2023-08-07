<?php

$scripts = [
  'assets/libs/pdf.js/build/pdf.js',
  'assets/js/archive/view.js'
];

$styles = [
  'assets/css/archive/view.css'
];

page_header('View | EO Archive', $scripts, $styles);

?>

<div class="container pt-5">
  <div class="row">
    <div class="col-12 col-sm-4 col-lg-3 mb-2 mb-sm-0">
      <div class="d-flex mb-2">
        <a href="/" class="btn btn-outline-dark me-2" role="button">
          <i class="fas fa-arrow-left me-1"></i>
          <span>Back to list</span>
        </a>

        <a href="/archive/edit/<?= $eo->id ?>" class="btn btn-outline-primary" role="button">
          <i class="fas fa-edit me-1"></i>
          <span>Edit</span>
        </a>
      </div>

      <div class="card bg-light mb-2">
        <div class="card-body">
          <h5 class="card-title"><?= $eo->description ?></h5>
          <div>Execute No. <strong><?= $eo->number ?></strong></div>
          <div>Series of <?= $eo->series ?></div>
          <div>Author: <?= $eo->author ?></div>
          <div>Author Position: <?= $eo->author_position ?></div>
          <div>Approved By: <?= $eo->approved_by ?></div>
          <div>Date Approved: <?= $eo->date_approved ?></div>
        </div>
      </div>

      <div class="d-flex flex-column">
        <a href="/archive/service/Archive_service/get_pdf/<?= $eo->id ?>" target="_blank" class="btn btn-primary mb-2" role="button">Open PDF in new tab</a>
        <div class="d-flex">
          <button type="button" id="pdf-prev" class="btn btn-outline-info px-3">
            <i class="fas fa-caret-left"></i>
          </button>

          <div class="d-flex mx-2">
            <input type="number" id="pdf-page" class="form-control" value="1" required />
            <div class="d-flex align-items-center mx-3">/</div>
            <input type="number" id="pdf-total-pages" class="form-control" value="" readonly />
          </div>

          <button type="button" id="pdf-next" class="btn btn-outline-info px-3">
            <i class="fas fa-caret-right"></i>
          </button>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-8 col-lg-9">
      <canvas id="canvas-pdf" class="shadow"></canvas>
    </div>
  </div>
</div>

<?php page_footer(); ?>
