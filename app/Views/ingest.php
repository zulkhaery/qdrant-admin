<div class="page-header">
    <h1>Document Ingestion</h1>
    <p>Insert content into Qdrant collection</p>
</div>

<div class="table-card">
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Collection</label>
            <select name="collection" class="form-control">
                <?php foreach ($collections as $item): ?>
                    <option value="<?= $item['name'] ?>">
                        <?= $item['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" class="form-control">
        </div>
        <div class="form-group">
            <label>PDF File</label>
            <input type="file" name="pdf" accept=".pdf" class="form-control">
        </div>
        <div class="form-group">
            <label>Content</label>
           <textarea name="content" rows="12" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn-primary">Insert Data</button>
    </form>
</div>