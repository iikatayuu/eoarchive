<?php

$scripts = ['assets/js/archive/index.js'];
$styles = ['assets/css/archive/index.css'];
page_header('EO Archive', $scripts, $styles);

?>

<div class="container pt-4">
  <form id="archive-search" class="col-12 col-lg-6 mb-3">
    <div class="input-group">
      <input type="search" id="archive-search-q" class="form-control" placeholder="Executive Order Title/Description" aria-label="Executive Order Title/Description" aria-describedby="btn-item-search" />
      <button type="submit" class="btn btn-success" id="btn-eo-search">
        <i class="fas fa-search me-1"></i>
        <span>Search</span>
      </button>
    </div>

    <button type="button" id="btn-adv-search" class="btn btn-link text-decoration-none">
      <i class="fas fa-filter fa-sm"></i>
      <span>Advanced Search</span>
    </button>
  </form>

  <form id="archive-advanced-search" class="col-12 col-lg-6 mb-3">
    <div class="row">
      <div class="col-12 col-sm-6">
        <div class="form-group mb-2">
          <label for="archive-search-description">Description:</label>
          <input type="text" id="archive-search-description" class="form-control" placeholder="Title/Description" />
        </div>

        <div class="form-group mb-2">
          <label for="archive-search-author">Author:</label>
          <input type="text" id="archive-search-author" class="form-control" placeholder="Author" />
        </div>

        <div class="form-group mb-2">
          <label for="archive-search-approved-date-from">Approved Date From:</label>
          <input type="date" id="archive-search-approved-date-from" class="form-control" />
        </div>
      </div>

      <div class="col-12 col-sm-6">
        <div class="form-group mb-2">
          <label for="archive-search-series">Series:</label>
          <input type="text" id="archive-search-series" class="form-control" placeholder="Series" />
        </div>

        <div class="form-group mb-2">
          <label for="archive-search-approved">Approved By:</label>
          <input type="text" id="archive-search-approved" class="form-control" placeholder="Approved By" />
        </div>

        <div class="form-group mb-2">
          <label for="archive-search-approved-date-to">Approved Date To:</label>
          <input type="date" id="archive-search-approved-date-to" class="form-control" />
        </div>
      </div>
    </div>

    <div class="d-flex">
      <button type="submit" class="btn btn-success">
        <i class="fas fa-search me-1"></i>
        <span>Search</span>
      </button>

      <button type="button" id="btn-simple-search" class="btn btn-link text-decoration-none">
        <i class="fas fa-filter fa-sm"></i>
        <span>Simple Search</span>
      </button>
    </div>
  </form>

  <div class="d-flex justify-content-between mb-2">
    <h4>Executive Orders</h4>
    <a href="/archive/add" class="btn btn-primary" role="button">
      <i class="fas fa-plus me-1"></i>
      <span>Add new</span>
    </a>
  </div>

  <table id="table-eos-container" class="table table-striped shadow">
    <thead class="table-dark">
      <tr>
        <th scope="col">EO Number</th>
        <th scope="col">Description</th>
        <th scope="col">Author</th>
        <th scope="col">Date Approved</th>
        <th scope="col">Options</th>
      </tr>
    </thead>

    <tbody id="table-eos"></tbody>
  </table>

  <nav aria-label="Executive Orders navigation">
    <ul id="eos-pagination" class="pagination justify-content-center">
      <li class="page-item">
        <a id="eos-prev" class="page-link" href="#" aria-label="Previous">
          <span aria-hidden="true">&laquo;</span>
        </a>
      </li>

      <li class="page-item">
        <a id="eos-next" class="page-link" href="#" aria-label="Next">
          <span aria-hidden="true">&raquo;</span>
        </a>
      </li>
    </ul>
  </nav>

  <template id="temp-table-eo">
    <tr>
      <td class="eo-number" scope="row"></td>
      <td class="eo-description"></td>
      <td class="eo-author"></td>
      <td class="eo-date-approved"></td>
      <td>
        <a href="/archive/view/" class="btn btn-success btn-sm eo-view" title="View executive order" role="button">
          <i class="fas fa-eye"></i>
        </a>

        <a href="/archive/edit/" class="btn btn-primary btn-sm eo-edit" title="Edit executive order" role="button">
          <i class="fas fa-edit"></i>
        </a>

        <button class="btn btn-danger btn-sm eo-delete" title="Delete executive order">
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
