<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Qdrant Admin - <?= $title ?></title>
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
<script>

const templates = {

match: `{
  "must": [
    {
      "key": "title",
      "match": {
        "value": "test"
      }
    }
  ]
}`,

range: `{
  "must": [
    {
      "key": "chunk_index",
      "range": {
        "gte": 10,
        "lte": 100
      }
    }
  ]
}`,

should: `{
  "should": [
    {
      "key": "title",
      "match": {
        "value": "halal"
      }
    },
    {
      "key": "title",
      "match": {
        "value": "bpjph"
      }
    }
  ]
}`,

must_not: `{
  "must_not": [
    {
      "key": "title",
      "match": {
        "value": "draft"
      }
    }
  ]
}`

};

const operator = document.getElementById('operator');
const filter = document.getElementById('filter');

if (operator && filter && !filter.value.trim()) {
    filter.value = templates[operator.value];
}

operator?.addEventListener('change', function () {
    filter.value = templates[this.value];
});

</script>
</body>
</html>