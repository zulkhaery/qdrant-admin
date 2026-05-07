<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
     <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="layout">

    <!-- SIDEBAR -->
    <?php require __DIR__ . '/../partials/sidebar.php'; ?>

    <!-- CONTENT -->
   <main class="content">
     <?php require __DIR__ . '/../' . $view . '.php'; ?>
</main>

</div>

</body>
</html>