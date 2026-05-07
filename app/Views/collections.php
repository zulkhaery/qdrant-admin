<div class="page-header">
    <h1>Collections</h1>
    <p>Manage and inspect Qdrant collections</p>
</div>

<div class="table-card">

    <table class="table">

        <thead>
            <tr>
                <th>Name</th>
                <th>Vectors</th>
                <th>Dimension</th>
                <th>Distance</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>

            <?php if (empty($collections)): ?>

                <tr>
                    <td colspan="5">
                        No collections found
                    </td>
                </tr>

            <?php endif; ?>

            <?php foreach ($collections as $item): ?>

                <tr>

                    <td>

                        <div class="collection-name">

                            <i class="bi bi-folder2-open"></i>

                            <?= $item['name'] ?>

                        </div>

                    </td>

                    <td>
                        <?= number_format($item['vectors']) ?>
                    </td>

                    <td>
                        <?= $item['dimension'] ?>
                    </td>

                    <td>

                        <span class="distance-badge">
                            <?= $item['distance'] ?>
                        </span>

                    </td>

                    <td>
                        <?php
                        $statusClass = match($item['status']) {
                            'green' => 'success',
                            'yellow' => 'warning',
                            'red' => 'danger',
                            default => 'gray'
                        };

                        ?>

                        <span class="badge <?= $statusClass ?>">
                            <?= $item['status'] ?>
                        </span>

                    </td>

                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

</div>