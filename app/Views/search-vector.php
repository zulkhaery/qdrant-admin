<div class="page-header">
    <div class="page-header-icon">
        <i class="bi bi-search"></i>
    </div>
    <div class="page-header-content">
        <h1><?= $title ?></h1>
        <p>Semantic vector similarity search</p>
    </div>
</div>


<div class="table-card">
    <form method="GET" class="filter-form">
        <select name="collection" class="form-control">
            <option value="">Select Collection</option>
            <?php foreach ($collections as $item): ?>
                <option value="<?= $item['name'] ?>" <?= $collection == $item['name'] ? 'selected' : '' ?>>
                    <?= $item['name'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <input type="text" name="query" value="<?= htmlspecialchars($query) ?>" placeholder="Search..." class="form-control">

        <button type="submit" class="btn-primary">Search</button>
    </form>
</div>

<?php if (!empty($results)): ?>
    <div class="table-card mt-20">
        <table class="table">
            <thead>
                <tr>
                    <th>Score</th>
                    <th>Payload</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $item): ?>
                    <tr>
                        <td><?= round($item['score'], 4) ?></td>
                        <td>
                            <pre class="payload-json"><?= htmlspecialchars(json_encode($item['payload'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></pre>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>