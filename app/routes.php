<?php

use App\Controllers\IndexController;

return [
    '/' => [IndexController::class, 'dashboard'],
    '/collections' => [IndexController::class, 'collections'],
    '/view-data' => [IndexController::class, 'viewData'],
    '/input-data' => [IndexController::class, 'inputData'],
    '/search-vector' => [IndexController::class, 'searchVector'],
    '/hybrid-search' => [IndexController::class, 'hybridSearch'],
    '/payload-explorer' => [IndexController::class, 'payloadExplorer'],
    '/embedding-test' => [IndexController::class, 'embeddingTest'],
    '/collection-schema' => [IndexController::class, 'collectionSchema'],
];