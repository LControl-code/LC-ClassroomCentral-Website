<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home page</title>
  <?php include_once __DIR__ . "/modules/head-tags.php"; ?>
  <link rel="stylesheet" href="index.css">
  <script src="index.js" defer></script>
</head>

<body>
  <?php include_once __DIR__ . "/modules/header.php"; ?>

  <main>
    <?php include_once __DIR__ . "/modules/search-form.php"; ?>
    <?php include_once __DIR__ . "/modules/categories-selector.php"; ?>
    <?php include_once __DIR__ . "/modules/spinner-and-course-container.php"; ?>
    <?php include_once __DIR__ . "/modules/pagination-buttons.php"; ?>
  </main>

  <?php include_once __DIR__ . "/modules/footer.php"; ?>
</body>

</html>