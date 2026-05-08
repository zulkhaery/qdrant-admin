<div class="page-header">
    <h1>View Data</h1>
    <p>Browse collection points and payloads</p>
</div>

<div class="table-card">
    <form method="GET" class="filter-form">
        <select name="collection">
            <option value="">Select Collection</option>
            <?php foreach ($collections as $item): ?>
                <option value="<?= $item['name'] ?>"
                    <?= $selectedCollection == $item['name'] ? 'selected' : '' ?>>
                    <?= $item['name'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Load Data</button>
    </form>
</div>

<?php if ($selectedCollection): ?>
    <div class="table-card mt-20">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Payload</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($points as $point): ?>

                  <tr>
                    <td valign="top"><?= $point['id'] ?></td>
                    <td class="vector-payload">
                        <pre class="payload-json"><?= htmlspecialchars(json_encode(
                            $point['payload'],
                            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
                        )) ?></pre>

                    <div class="vector-section">
                        <details>
                            <summary>Vector Dimension: <?= count($point['vector'] ?? []) ?>
                            <a class="btn-remove remove-vector" href="/remove-vector?collection=<?= $selectedCollection ?>&id=<?= $point['id'] ?>" onclick="return confirm('Delete vector ?')">
                                <i class="bi bi-trash3"></i>
                                Remove Vector
                            </a>
                        </summary>
                            <pre class="vector-json"><?= json_encode(
                                $point['vector'] ?? [],
                                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
                            ) ?></pre>
                            
                        </details>
                    </div>
                    
                </td>
            </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>