<?php

use App\Controllers\DashboardController;
use App\Controllers\CollectionController;
use App\Controllers\DataController;
use App\Controllers\SearchController;
use App\Controllers\EmbeddingController;
use App\Controllers\ChunkInspectorController;

return [
    '/' => [DashboardController::class, 'index'],
    '/collections' => [CollectionController::class, 'index'],
    '/collections/add' => [CollectionController::class, 'add'],
    '/collections/remove' => [CollectionController::class, 'remove'],
    '/view-data' => [DataController::class, 'index'],
    '/ingest' => [DataController::class, 'ingest'],
    '/remove-vector' => [DataController::class, 'removeVector'],
    '/search-vector' => [SearchController::class, 'searchVector'],
    '/payload-explorer' => [SearchController::class, 'payloadExplorer'],
    '/embedding-test' => [EmbeddingController::class, 'embeddingTest'],
    '/collection-schema' => [CollectionController::class, 'schema'],
    '/collection-schema/create-index' => [CollectionController::class, 'addIndex'],
    '/collection-schema/delete-index' => [CollectionController::class, 'deleteIndex'],
    '/chunk-inspector' => [ChunkInspectorController::class, 'index'],
];