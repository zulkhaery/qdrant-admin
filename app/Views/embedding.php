<div class="page-header">
    <h1>Embedding Test</h1>
    <p>Test semantic similarity between two texts</p>
</div>

<div class="table-card">
    <form method="POST">
        <div class="form-group">
            <label>Text A</label>
            <textarea name="text_a" rows="6" class="form-control"><?= htmlspecialchars($textA) ?></textarea>
        </div>

        <div class="form-group">
            <label>Text B</label>
            <textarea name="text_b" rows="6" class="form-control"><?= htmlspecialchars($textB) ?></textarea>
        </div>

        <div class="form-group">
            <label>Similarity Method</label>
            <select name="method" class="form-control">
                <option value="cosine" <?= $method == 'cosine' ? 'selected' : '' ?>>Cosine Similarity</option>
                <option value="dot" <?= $method == 'dot' ? 'selected' : '' ?>>Dot Product</option>
                <option value="euclid" <?= $method == 'euclid' ? 'selected' : '' ?>>Euclidean Distance</option>
            </select>
        </div>

        <button type="submit" class="btn-primary">Generate Embedding</button>
    </form>
</div>

<?php if ($similarity !== null): ?>
    <div class="dashboard-grid mt-20">
        <div class="dashboard-card">
            <div class="card-info">
                <span class="card-title">Embedding Model</span>
                <h2>nomic-embed-text</h2>
                <p>Ollama Provider</p>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-info">
                <span class="card-title">Vector Dimension</span>
                <h2><?= count($vectorA) ?></h2>
                <p>Embedding Size</p>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-info">
                <span class="card-title">Similarity Score</span>
                <h2><?= round($similarity, 4) ?></h2>
                <p><?= $method == 'cosine' ? 'Cosine Similarity' : ($method == 'dot' ? 'Dot Product' : 'Euclidean Distance') ?></p>
            </div>
        </div>
    </div>

    <div class="table-card mt-20">
        <details>
            <summary>View Vector A</summary>
            <pre class="vector-json"><?= json_encode($vectorA, JSON_PRETTY_PRINT) ?></pre>
        </details>

        <details class="mt-20">
            <summary>View Vector B</summary>
            <pre class="vector-json"><?= json_encode($vectorB, JSON_PRETTY_PRINT) ?></pre>
        </details>
    </div>
<?php endif; ?>