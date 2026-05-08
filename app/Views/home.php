<div class="page-header">
    <div class="page-header-icon">
        <i class="bi bi-grid"></i> 
    </div>
    <div class="page-header-content">
        <h1><?= $title ?></h1>
        <p>Qdrant Vector Database Dashboard</p>
    </div>
</div>

    <div class="dashboard-grid">

        <div class="dashboard-card">
            <div class="card-icon blue">
                <i class="bi bi-hdd-network"></i>
            </div>

            <div class="card-info">
                <span class="card-title">Qdrant Status</span>
                <h2><?= $stats['qdrant_status'] ?></h2>
                <p>localhost:6333</p>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-icon purple">
                <i class="bi bi-cpu"></i>
            </div>

            <div class="card-info">
                <span class="card-title">Embedding Model</span>
                <h2><?= $stats['embedding_model'] ?></h2>
                <p><?= $stats['dimension'] ?> Dimensions</p>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-icon green">
                <i class="bi bi-folder2-open"></i>
            </div>

            <div class="card-info">
                <span class="card-title">Collections</span>
                <h2><?= $stats['collections'] ?></h2>
                <p>Total Collections</p>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-icon orange">
                <i class="bi bi-search"></i>
            </div>

            <div class="card-info">
                <span class="card-title">Total Vectors</span>
                <h2><?= number_format($stats['total_vectors']) ?></h2>
                <p>Across all collections</p>
            </div>
        </div>
    </div>
    <div class="section">
        <div class="section-header">
            <h3>System Information</h3>
        </div>

        <div class="system-info">

    <div class="info-row">
        <div class="info-label">
            <i class="bi bi-box"></i>
            <span>Qdrant Version</span>
        </div>

        <strong><?= $stats['qdrant_version'] ?></strong>
    </div>

    <div class="info-row">
        <div class="info-label">
            <i class="bi bi-cpu"></i>
            <span>Embedding Model</span>
        </div>

        <strong><?= $stats['embedding_model'] ?></strong>
    </div>

    <div class="info-row">
        <div class="info-label">
            <i class="bi bi-distribute-horizontal"></i>
            <span>Vector Dimension</span>
        </div>

        <strong><?= $stats['dimension'] ?></strong>
    </div>

    <div class="info-row">
        <div class="info-label">
            <i class="bi bi-hdd-network"></i>
            <span>Host</span>
        </div>

        <strong><?= $stats['host'] ?></strong>
    </div>

</div>

    </div>
    