<?php

function page_header ($title = 'EO Archive', $scripts = [], $styles = []) {
  $baseurl = base_url();
  $uristring = uri_string();
  $active = explode('/', $uristring)[0];

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="viewport-fit=cover, width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <meta name="msapplication-TileColor" content="#212529" />
  <meta name="msapplication-config" content="<?= $baseurl ?>assets/browserconfig.xml" />
  <meta name="theme-color" content="#212529" />

  <link rel="apple-touch-icon" sizes="180x180" href="<?= $baseurl ?>assets/apple-touch-icon.png" />
  <link rel="icon" type="image/png" sizes="32x32" href="<?= $baseurl ?>assets/favicon-32x32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="<?= $baseurl ?>assets/favicon-16x16.png" />
  <link rel="manifest" href="<?= $baseurl ?>assets/site.webmanifest" />
  <link rel="mask-icon" href="<?= $baseurl ?>/assets/safari-pinned-tab.svg" color="#212529" />
  <link rel="shortcut icon" href="<?= $baseurl ?>assets/favicon.ico" />

  <link rel="stylesheet" href="<?= $baseurl ?>assets/libs/bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="<?= $baseurl ?>assets/css/default.css" />
  <?php foreach ($styles as $key => $style): ?>
  <link rel="stylesheet" href="<?= $baseurl ?><?= $style ?>" />
  <?php endforeach; ?>

  <script src="<?= $baseurl ?>assets/libs/jquery/jquery.min.js"></script>
  <script src="<?= $baseurl ?>assets/libs/bootstrap/js/bootstrap.min.js"></script>
  <script src="<?= $baseurl ?>assets/libs/fontawesome/js/all.min.js" defer></script>
  <script src="<?= $baseurl ?>assets/js/default.js"></script>
  <?php foreach ($scripts as $key => $script): ?>
  <script src="<?= $baseurl ?><?= $script ?>"></script>
  <?php endforeach; ?>

  <title><?= $title ?></title>
</head>

<body>
  <header class="p-3 bg-dark text-white">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-start justify-content-sm-between">
        <a href="<?= $baseurl ?>" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
          <img src="<?= $baseurl ?>assets/imgs/logo.png" alt="Food Inventory Logo" width="32" height="32" class="bi me-2" />
        </a>

        <ul class="nav">
          <li>
            <a href="/" class="nav-link px-2 <?= $active === '' ? 'text-white' : 'text-secondary' ?>">
              <i class="fas fa-archive me-1"></i>
              <span>Archive</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </header>

  <main>
<?php
}

function page_footer () {
?>
  </main>

  <div id="modal-ask" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body"></div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <template id="temp-ask-btn">
    <button type="button" class="btn"></button>
  </template>

  <footer class="container">
    <div class="text-center">All rights reserved. 2023</div>
  </footer>
</body>
</html>

<?php
}