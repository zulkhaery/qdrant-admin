<div class="page-header">
    <h1>Add Collection</h1>
    <p>Create new Qdrant collection</p>
</div>

<div class="table-card">

    <form method="POST">

        <div class="form-group">
            <label>Collection Name</label>
            <input type="text" name="name" class="form-control">
        </div>

        <div class="form-group">
            <label>Vector Dimension</label>
            <input type="number" name="size" value="768" class="form-control">
        </div>

        <div class="form-group">
            <label>Distance Metric</label>

            <select name="distance" class="form-control">
                <option value="Cosine">Cosine</option>
                <option value="Dot">Dot</option>
                <option value="Euclid">Euclid</option>
                <option value="Manhattan">Manhattan</option>
            </select>
        </div>

        <button type="submit" class="btn-primary">
            Create Collection
        </button>

    </form>

</div>