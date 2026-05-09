<aside class="sidebar">

    <div class="logo">
        Qdrant Admin
    </div>
<nav class="menu">

<a href="/" class="<?= $currentPath == '/' ? 'active' : '' ?>">
     <i class="bi bi-grid"></i> 
    Dashboard
</a>

<a href="/collections" class="<?= $currentPath == '/collections' || $currentPath == '/collections/add' ? 'active' : '' ?>">
    <i class="bi bi-folder2-open"></i>
    Collections
</a>

<a href="/view-data" class="<?= $currentPath == '/view-data' ? 'active' : '' ?>">
    <i class="bi bi-database"></i>
    Data Explorer
</a>

<a href="/ingest" class="<?= $currentPath == '/ingest' ? 'active' : '' ?>">
    <i class="bi bi-file-earmark-arrow-up"></i>
    Document Ingestion
</a>

<a href="/search-vector" class="<?= $currentPath == '/search-vector' ? 'active' : '' ?>">
    <i class="bi bi-search"></i>
    Search Vector
</a>


<a href="/chunk-inspector" class="<?= $currentPath == '/chunk-inspector' ? 'active' : '' ?>">
    <i class="bi bi-incognito"></i>
    Chunk Inspector
</a>

<a href="/payload-explorer" class="<?= $currentPath == '/payload-explorer' ? 'active' : '' ?>">
    <i class="bi bi-braces"></i>
    Payload Explorer
</a>

<a href="/collection-schema" class="<?= $currentPath == '/collection-schema' ? 'active' : '' ?>">
    <i class="bi bi-diagram-3"></i>
    Collection Schema
</a>

<a href="/embedding-test" class="<?= $currentPath == '/embedding-test' ? 'active' : '' ?>">
    <i class="bi bi-cpu"></i>
    Embedding Test
</a>


</nav>

</aside>