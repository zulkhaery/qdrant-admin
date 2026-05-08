<div class="page-header">
    <div class="page-header-icon">
        <i class="bi bi-braces"></i>
    </div>
    <div class="page-header-content">
        <h1><?= $title ?></h1>
        <p>Explore and test Qdrant payload filters</p>
    </div>
</div>

<div class="table-card">
    <form method="POST">
        <div class="form-group">
            <label>Collection</label>
            <select name="collection" class="form-control">
                <?php foreach ($collections as $item): ?>
                    <option value="<?= $item['name'] ?>" <?= $collection == $item['name'] ? 'selected' : '' ?>>
                        <?= $item['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Operator</label>
            <select id="operator" class="form-control">
                <option value="match">Match</option>
                <option value="range">Range</option>
                <option value="should">Should (OR)</option>
                <option value="must_not">Must Not</option>
            </select>
        </div>

        <div class="form-group">
            <label>Payload Filter JSON</label>
            <textarea id="filter" name="filter" rows="12" class="form-control code-textarea"><?= htmlspecialchars($filter ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn-primary">Execute Filter</button>
    </form>
</div>

<?php if (!empty($results)): ?>
    <div class="table-card mt-20">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Payload</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $item): ?>
                    <tr>
                        <td><?= $item['id'] ?></td>
                        <td>
                            <pre class="payload-json"><?= htmlspecialchars(json_encode($item['payload'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></pre>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>