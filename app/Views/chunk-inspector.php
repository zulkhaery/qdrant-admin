<div class="page-header">
    <div class="page-header-icon">
        <i class="bi bi-incognito"></i>
    </div>
    <div class="page-header-content">
        <h1><?= $title ?></h1>
        <p>Inspect chunks, semantic retrieval, and RAG context flow</p>
    </div>
</div>

<!-- TOOLBAR -->
<div class="rag-toolbar">
    <form method="GET" class="rag-search-form">
        <div class="toolbar-group">
            <label>Collection</label>
            <select name="collection">
                <option value="">
                    Select Collection
                </option>
                <?php foreach ($collections as $collection): ?>
                    <option value="<?= $collection['name'] ?>" <?= $selectedCollection == $collection['name'] ? 'selected' : '' ?>>
                        <?= $collection['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="toolbar-group search-box">
            <label>Semantic Query</label>
            <input type="text" name="query" value="<?= htmlspecialchars($_GET['query'] ?? '') ?>" placeholder="Search semantic chunks...">
        </div>

        <div class="toolbar-button">
            <button class="btn-primary">
                <i class="bi bi-search"></i>
                Search
            </button>
        </div>
    </form>
</div>

<?php if ($selectedCollection && !empty($chunks)): ?>
<div class="rag-layout">
    <!-- LEFT PANEL -->
    <div class="chunk-panel">
        <div class="panel-header">
            <h3>Retrieved Chunks</h3>
            <span class="chunk-count"><?= count($chunks) ?> chunks</span>
        </div>

        <?php if (empty($chunks)): ?>
            <div class="empty-state">
                <i class="bi bi-file-earmark-x"></i>
                <p>No chunks found</p>
            </div>
        <?php endif; ?>

        <?php foreach ($chunks as $chunk): ?>
            <?php
                $payload    = $chunk['payload'] ?? [];
                $content    = $payload['content'] ?? '';
                $chunkIndex = $payload['chunk_index'] ?? '-';
                $isActive   = ($selectedChunk['id'] ?? null) == ($chunk['id'] ?? null);
                $score      = $chunk['score'] ?? null;
            ?>
            <a href="/chunk-inspector?collection=<?= urlencode($selectedCollection) ?>&query=<?= urlencode($query) ?>&id=<?= $chunk['id'] ?>"
               class="chunk-card <?= $isActive ? 'active' : '' ?>">
                <div class="chunk-card-top">
                    <div class="chunk-title">
                        <i class="bi bi-file-earmark-text"></i>
                        Chunk #<?= $chunkIndex ?>
                    </div>
                    <?php if ($score !== null): ?>
                        <div class="similarity-score">
                            <?php if (strtolower($distanceMetric) == 'cosine'): ?>
                                <?= round($score * 100) ?>%
                            <?php elseif (strtolower($distanceMetric) == 'dot'): ?>
                                Dot <?= round($score, 4) ?>
                            <?php elseif (strtolower($distanceMetric) == 'euclid'): ?>
                                <?= round($score, 4) ?>
                            <?php elseif (strtolower($distanceMetric) == 'manhattan'): ?>
                                <?= round($score, 4) ?>
                            <?php else: ?>
                                <?= round($score, 4) ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="chunk-preview">
                    <?= substr(strip_tags($content), 0, 220) ?>...
                </div>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- RIGHT PANEL -->
    <div class="inspector-panel">
        <?php if ($selectedChunk): ?>
            <?php
                $payload      = $selectedChunk['payload'] ?? [];
                $content      = $payload['content'] ?? '';
                $chunkIndex   = $payload['chunk_index'] ?? '-';
                $documentName = $payload['title'] ?? 'Untitled Document';
                $vectorSize   = count($selectedChunk['vector'] ?? []);
            ?>
            <div class="inspector-header">
                <div>
                    <h2>Chunk #<?= $chunkIndex ?></h2>
                    <p><?= $documentName ?></p>
                </div>
                <span class="badge success">Loaded</span>
            </div>

            <div class="inspector-grid">
                <div class="meta-box">
                    <label>Chunk ID</label>
                    <strong><?= $selectedChunk['id'] ?></strong>
                </div>
                <div class="meta-box">
                    <label>Vector Size</label>
                    <strong><?= $vectorSize ?></strong>
                </div>
                <div class="meta-box">
                    <label>Content Length</label>
                    <strong><?= strlen($content) ?></strong>
                </div>
                <div class="meta-box">
                    <label>Distance Metric</label>
                    <strong><?= $distanceMetric ?></strong>
                </div>
            </div>

            <div class="content-card">
                <div class="content-header">
                    <i class="bi bi-braces"></i>
                    Chunk Content
                </div>
                <div class="chunk-content">
                    <?= nl2br(htmlspecialchars($content)) ?>
                </div>
            </div>

            <?php if (!empty($chunks)): ?>
                <?php
                    $relatedChunks = array_filter($chunks, fn($item) => ($item['id'] ?? null) != ($selectedChunk['id'] ?? null));
                    $relatedChunks = array_slice($relatedChunks, 0, 5);
                ?>
                <div class="related-card">
                    <div class="content-header">
                        <i class="bi bi-share"></i>
                        Related Chunks
                    </div>
                    <div class="related-list">
                        <?php foreach ($relatedChunks as $related): ?>
                            <?php
                                $relatedPayload = $related['payload'] ?? [];
                                $relatedIndex   = $relatedPayload['chunk_index'] ?? '-';
                                $relatedScore   = $related['score'] ?? null;
                            ?>
                            <a href="/chunk-inspector?collection=<?= urlencode($selectedCollection) ?>&query=<?= urlencode($query) ?>&id=<?= $related['id'] ?>"
                               class="related-item">
                                <span>Chunk #<?= $relatedIndex ?></span>
                                <?php if ($relatedScore !== null): ?>
                                    <strong>
                                        <?php if (strtolower($distanceMetric) == 'cosine'): ?>
                                            <?= round($relatedScore * 100) ?>%
                                        <?php elseif (strtolower($distanceMetric) == 'dot'): ?>
                                            Dot <?= round($relatedScore, 4) ?>
                                        <?php elseif (strtolower($distanceMetric) == 'euclid'): ?>
                                            <?= round($relatedScore, 4) ?>
                                        <?php elseif (strtolower($distanceMetric) == 'manhattan'): ?>
                                            <?= round($relatedScore, 4) ?>
                                        <?php else: ?>
                                            <?= round($relatedScore, 4) ?>
                                        <?php endif; ?>
                                    </strong>
                                <?php else: ?>
                                    <strong>-</strong>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="empty-state inspector-empty">
                <i class="bi bi-search"></i>
                <h3>No Chunk Selected</h3>
                <p>Select a chunk from the left panel to inspect payload and content</p>
            </div>
        <?php endif; ?>
    </div>

    <?php endif; ?>
</div>