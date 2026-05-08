<div class="page-header page-header-flex">
    <div>
        <h1>Collections</h1>
        <p>Manage and inspect Qdrant collections</p>
    </div>
    <a href="/collections/add" class="btn-primary">
        <i class="bi bi-plus-lg"></i>
        Add Collection
    </a>
</div>

<div class="table-card">
    <table class="table" >
        <thead>
            <tr>
                <th>Name</th>
                <th>Vectors</th>
                <th>Dimension</th>
                <th>Distance</th>
                <th>Status</th>
                <th style="width:10px !important"></th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($collections)): ?>
                <tr>
                    <td colspan="5">No collections found</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($collections as $item): ?>
                <?php
                $statusClass = match($item['status']) {
                    'green'  => 'success',
                    'yellow' => 'warning',
                    'red'    => 'danger',
                    default  => 'gray'
                };
                ?>
                <tr>
                    <td>
                        <div class="collection-name">
                            <i class="bi bi-folder2-open"></i>
                            <?= $item['name'] ?>
                        </div>
                    </td>
                    <td><?= number_format($item['vectors']) ?></td>
                    <td><?= $item['dimension'] ?></td>
                    <td><span class="distance-badge"><?= $item['distance'] ?></span></td>
                    <td><span class="badge <?= $statusClass ?>"><?= $item['status'] ?></span></td>
                    <td> 
                        <a class="btn-remove remove-vector" href="/collections/remove?name=<?= $item['name'] ?>" onclick="return confirm('Delete Collection ?')">
                            <i class="bi bi-trash3"></i> 
                            </a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>