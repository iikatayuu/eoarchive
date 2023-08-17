<?php

$baseurl = base_url();
$scripts = ['assets/js/archive/edit.js'];
page_header('Edit | EO Archive', $scripts);

?>

<div class="container pt-4">
  <form action="" method="post" id="archive-edit" class="card bg-light col-12 col-sm-6 mx-auto mb-3">
    <div class="card-body">
      <h5 class="card-title">Edit Executive Order</h5>
      <input type="hidden" id="eo-id" name="eo-id" value="<?= $eo->id ?>" required />

      <div class="row">
        <div class="col-12 col-sm-6">
          <div class="form-group mb-2">
            <label for="eo-num">EO Number:</label>
            <input type="number" id="eo-num" name="eo-num" class="form-control" value="<?= $eo->number ?>" required />
          </div>

          <div class="form-group mb-2">
            <label for="eo-series">Series:</label>
            <input type="text" id="eo-series" name="eo-series" class="form-control" value="<?= $eo->series ?>" required />
          </div>

          <div class="form-group mb-2">
            <label for="eo-description">Description:</label>
            <textarea id="eo-description" name="eo-description" class="form-control" rows="2" required><?= $eo->description ?></textarea>
          </div>

          <div class="form-group mb-2">
            <label for="eo-file">File (Optional):</label>
            <input type="file" id="eo-file" name="eo-file" class="form-control" accept="application/pdf" />
          </div>
        </div>

        <div class="col-12 col-sm-6">
          <div class="form-group mb-2">
            <label for="eo-author">Author:</label>
            <input type="text" id="eo-author" name="eo-author" class="form-control" value="<?= $eo->author ?>" required />
          </div>

          <div class="form-group mb-2">
            <label for="eo-author-position">Author Position:</label>
            <input type="text" id="eo-author-position" name="eo-author-position" class="form-control" value="<?= $eo->author_position ?>" required />
          </div>

          <div class="form-group mb-2">
            <label for="eo-approved">Approved By:</label>
            <input type="text" id="eo-approved" name="eo-approved" class="form-control" value="<?= $eo->approved_by ?>" required />
          </div>

          <div class="form-group mb-2">
            <label for="eo-approved-date">Date Approved:</label>
            <input type="date" id="eo-approved-date" name="eo-approved-date" class="form-control" value="<?= $eo->date_approved ?>" required />
          </div>
        </div>
      </div>

      <div class="alert alert-danger form-error" role="alert"></div>

      <div class="alert alert-success form-success" role="alert"></div>
    </div>

    <div class="card-footer text-end">
      <a href="<?= $baseurl ?>" class="btn btn-secondary" role="button">Cancel</a>

      <button type="submit" class="btn btn-primary">
        <i class="fas fa-edit me-1"></i>
        <span class="btn-display">Save Changes</span>
        <span class="btn-process">Saving Changes...</span>
      </button>
    </div>
  </form>
</div>

<?php page_footer(); ?>
