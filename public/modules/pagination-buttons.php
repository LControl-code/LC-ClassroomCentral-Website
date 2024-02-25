<div>
  <button id="previous-page" data-page="<?php
  $page = (int) filter_var($_GET['page'] ?? 1, FILTER_SANITIZE_NUMBER_INT);
  if ($page - 1 <= 1) {
    echo 1;
  } else {
    echo $page - 1;
  }
  ?>">Previous Page</button>

  <button id="next-page" data-page="<?php
  $page = (int) filter_var($_GET['page'] ?? 1, FILTER_SANITIZE_NUMBER_INT);
  if ($page < 1) {
    echo 1;
  } else {
    echo $page + 1;
  }
  ?>">Next Page</button>
</div>