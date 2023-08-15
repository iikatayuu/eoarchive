<?php

$scripts = ['assets/js/admins/index.js'];
page_header('Administrators | EO Archive', $scripts);

?>

<div class="container pt-4">
  <div class="row">
    <div class="col-12 col-md-4">
      <form action="" method="post" id="admins-add" class="card bg-light mb-3">
        <div class="card-body">
          <h5>Add new admin</h5>

          <div class="form-group mb-2">
            <label for="admin-username">Username:</label>
            <input type="text" id="admin-username" name="username" class="form-control" required />
          </div>

          <div class="form-group mb-2">
            <label for="admin-password">Password:</label>
            <input type="password" id="admin-password" name="password" class="form-control" required />
          </div>

          <div class="alert alert-danger form-error" role="alert"></div>

          <div class="alert alert-success form-success" role="alert"></div>

          <button type="submit" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>
            <span class="btn-display">Add</span>
            <span class="btn-process">Adding...</span>
          </button>
        </div>
      </form>
    </div>

    <div class="col-12 col-md-8">
      <form id="admins-search" class="d-flex align-items-center justify-content-between mb-3">
        <div class="col-12 col-lg-6">
          <div class="input-group">
            <input type="search" id="admins-search-q" class="form-control" placeholder="Administrator's username" aria-label="Administrator's username" aria-describedby="btn-admin-search" />
            <button type="submit" class="btn btn-success" id="btn-admin-search">
              <i class="fas fa-search me-1"></i>
              <span>Search</span>
            </button>
          </div>
        </div>

        <a href="/admins/logout" class="btn btn-danger" role="button">
          <i class="fas fa-sign-out-alt me-1"></i>
          <span>Sign Out</span>
        </a>
      </form>

      <h4 class="mb-2">Administrators</h4>
      <div class="table-responsive">
        <table id="table-admins-container" class="table table-striped shadow">
          <thead class="table-dark">
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Username</th>
              <th scope="col">Options</th>
            </tr>
          </thead>

          <tbody id="table-admins"></tbody>
        </table>
      </div>

      <nav aria-label="Admins navigation">
        <ul id="admins-pagination" class="pagination justify-content-center">
          <li class="page-item">
            <a id="admins-prev" class="page-link" href="#" aria-label="Previous">
              <span aria-hidden="true">&laquo;</span>
            </a>
          </li>

          <li class="page-item">
            <a id="admins-next" class="page-link" href="#" aria-label="Next">
              <span aria-hidden="true">&raquo;</span>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </div>

  <div id="modal-admin-edit" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <form id="admin-edit" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" id="admin-edit-id" value="" />

          <div class="form-group mb-2">
            <label for="admin-edit-password">New Password:</label>
            <input type="password" id="admin-edit-password" class="form-control" value="" required />
          </div>

          <div class="alert alert-danger form-error" role="alert"></div>

          <div class="alert alert-success form-success" role="alert"></div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i>
            <span class="btn-display">Save Changes</span>
            <span class="btn-process">Saving...</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <template id="temp-table-admin">
    <tr>
      <td class="admin-id" scope="row"></td>
      <td class="admin-username"></td>
      <td>
        <button class="btn btn-primary btn-sm admin-edit" title="Change admin's password">
          <i class="fas fa-edit"></i>
        </button>

        <button class="btn btn-danger btn-sm admin-delete" title="Delete administrator">
          <i class="fas fa-trash"></i>
        </button>
      </td>
    </tr>
  </template>

  <template id="temp-page">
    <li class="page-item">
      <a class="page-link" href="#"></a>
    </li>
  </template>
</div>

<?php page_footer(); ?>
