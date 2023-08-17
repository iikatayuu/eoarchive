<?php

$scripts = ['assets/js/admins/login.js'];
page_header('Admin Sign In | EO Archive', $scripts);

?>

<div class="container pt-4">
  <form action="" method="post" id="admin-login" class="card bg-light col-10 col-sm-7 col-md-5 col-lg-3 mx-auto mt-5 mb-3">
    <div class="card-body">
      <h5 class="card-title">Administrator's Log In</h5>

      <div class="form-group mb-2">
        <label for="admin-username">Username:</label>
        <input type="text" id="admin-username" name="username" class="form-control" value="" required />
      </div>

      <div class="form-group mb-2">
        <label for="admin-password">Password:</label>
        <input type="password" id="admin-password" name="password" class="form-control" value="" required />
      </div>

      <div class="alert alert-danger form-error" role="alert"></div>

      <div class="alert alert-success form-success" role="alert"></div>

      <div class="d-grid mt-3">
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-sign-in-alt me-1"></i>
          <span class="btn-display">Sign In</span>
          <span class="btn-process">Signing In...</span>
        </button>
      </div>
    </div>
  </form>
</div>

<?php page_footer(); ?>
