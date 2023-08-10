<?php

$scripts = ['assets/js/archive/add.js'];
page_header('Add New | EO Archive', $scripts);

?>

<div class="container pt-4">
  <form action="" method="post" id="archive-add" class="card bg-light col-12 col-sm-6 mx-auto">
    <div class="card-body">
      <h5 class="card-title">Add new Executive Order</h5>

      <div class="row">
        <div class="col-12 col-sm-6">
          <div class="form-group mb-2">
            <label for="eo-num">EO Number:</label>
            <input type="number" id="eo-num" name="eo-num" class="form-control" required />
          </div>

          <div class="form-group mb-2">
            <label for="eo-series">Series:</label>
            <input type="text" id="eo-series" name="eo-series" class="form-control" required />
          </div>

          <div class="form-group mb-2">
            <label for="eo-description">Description:</label>
            <textarea id="eo-description" name="eo-description" class="form-control" rows="2" required></textarea>
          </div>

          <div class="form-group mb-2">
            <label for="eo-file">File:</label>
            <input type="file" id="eo-file" name="eo-file" class="form-control" accept="application/pdf" required />
          </div>
        </div>

        <div class="col-12 col-sm-6">
          <div class="form-group mb-2">
            <label for="eo-author">Author:</label>
            <input type="text" id="eo-author" name="eo-author" class="form-control" required />
          </div>

          <div class="form-group mb-2">
            <label for="eo-author-position">Author Position:</label>
            <input type="text" id="eo-author-position" name="eo-author-position" class="form-control" required />
          </div>

          <div class="form-group mb-2">
            <label for="eo-approved">Approved By:</label>
            <input type="text" id="eo-approved" name="eo-approved" class="form-control" required />
          </div>

          <div class="form-group mb-2">
            <label for="eo-approved-date">Date Approved:</label>
            <input type="date" id="eo-approved-date" name="eo-approved-date" class="form-control" required />
          </div>
        </div>
      </div>

      <div class="alert alert-danger form-error" role="alert"></div>

      <div class="alert alert-success form-success" role="alert"></div>
    </div>

    <div class="card-footer text-end">
      <a href="/" class="btn btn-secondary" role="button">Cancel</a>

      <button type="submit" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>
        <span class="btn-display">Submit</span>
        <span class="btn-process">Submitting...</span>
      </button>
    </div>
  </form>
</div>

<?php page_footer(); ?>
