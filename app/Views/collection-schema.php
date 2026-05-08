<div class="page-header">
    <h1>Collection Schema</h1>
    <p>Inspect and configure Qdrant collection settings</p>
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

        <button type="submit" class="btn-primary">Load Schema</button>
    </form>
</div>

<?php if ($collection && $info): ?>
    <div class="dashboard-grid mt-20">
        <div class="dashboard-card">
            <div class="card-info">
                <span class="card-title">Vector Dimension</span>
                <h2><?= $info['result']['config']['params']['vectors']['size'] ?? '-' ?></h2>
                <p>Embedding Size</p>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-info">
                <span class="card-title">Distance Metric</span>
                <h2><?= $info['result']['config']['params']['vectors']['distance'] ?? '-' ?></h2>
                <p>Similarity Method</p>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-info">
                <span class="card-title">Points Count</span>
                <h2><?= number_format($info['result']['points_count'] ?? 0) ?></h2>
                <p>Total Stored Vectors</p>
            </div>
        </div>
    </div>

    <div class="table-card mt-20">
        <div class="section-header">
            <h3>Payload Fields</h3>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Field</th>
                    <th>Type</th>
                    <th>Index</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php $payloadSchema = $payloadSchema ?? []; ?>
                <?php foreach ($payloadFields as $field): ?>
                    <?php
                        $isIndexed = isset($payloadSchema[$field['field']]);
                        $indexType = $isIndexed ? $payloadSchema[$field['field']]['data_type'] : null;
                    ?>
                    <tr>
                        <td><?= $field['field'] ?></td>

                        <td>
                            <span class="distance-badge"><?= $field['type'] ?></span>
                        </td>

                        <td>
                            <?php if ($isIndexed): ?>
                                <span class="badge success"><?= $indexType ?></span>
                            <?php else: ?>
                                <span class="badge gray">null</span>
                            <?php endif; ?>
                        </td>

                        <td class="action-cell">
                            <div class="inline-form">
                                <form method="POST" action="/collection-schema/create-index" class="inline-form">
                                    <input type="hidden" name="collection" value="<?= $collection ?>">
                                    <input type="hidden" name="field" value="<?= $field['field'] ?>">

                                    <select name="schema" class="schema-select">
                                        <option value="keyword" <?= $indexType == 'keyword' ? 'selected' : '' ?>>keyword</option>
                                        <option value="integer" <?= $indexType == 'integer' ? 'selected' : '' ?>>integer</option>
                                        <option value="float" <?= $indexType == 'float' ? 'selected' : '' ?>>float</option>
                                        <option value="bool" <?= $indexType == 'bool' ? 'selected' : '' ?>>bool</option>
                                        <option value="text" <?= $indexType == 'text' ? 'selected' : '' ?>>text</option>
                                    </select>

                                    <button type="submit" class="btn-small"><?= $isIndexed ? 'Update' : 'Create' ?></button>
                                </form>

                                <?php if ($isIndexed): ?>
                                    <form method="POST" action="/collection-schema/delete-index">
                                        <input type="hidden" name="collection" value="<?= $collection ?>">
                                        <input type="hidden" name="field" value="<?= $field['field'] ?>">
                                        <button type="submit" class="btn-small btn-danger">Delete</button>
                                    </form>
                                <?php else: ?>
                                    <button type="button" class="btn-small btn-disabled">Delete</button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>