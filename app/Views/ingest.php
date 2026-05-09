<div class="page-header">
    <div class="page-header-icon">
       <i class="bi bi-file-earmark-arrow-up"></i>
    </div>
    <div class="page-header-content">
        <h1><?= $title ?></h1>
        <p>Insert content into Qdrant collection</p>
    </div>
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
            <label>Content (PDF)</label>
            <input type="file" name="pdf" accept=".pdf" class="form-control">
        </div>
        <div class="form-group">
            <label>Content (Text)</label>
           <textarea name="content" rows="12" class="form-control"></textarea>
        </div>
        <div class="form-group">

    <label>Custom Payload (JSON)</label>

    <textarea name="custom_payload" rows="10" class="form-control code-textarea"
        placeholder='{
  "category": "regulation",
  "year": 2025,
  "source": "BPJPH",
  "tags": ["halal", "sertifikasi"]
}'></textarea>

<details style="margin-top:15px;">
<summary >Example Stored Payload</summary>
<pre>
{
  "title"             : "...",
  "content"           : "...",
  "chunk_index"       : "...",
  "created_at"        : "...",
   "...custom_fields" : "...",
   "...custom_fields" : "..."
}    
</pre>
</details>
</div>
        <button type="submit" class="btn-primary">Insert Data</button>
    </form>
</div>