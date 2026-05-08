<?php

use App\Controllers\IndexController;

return [
    '/' => [IndexController::class, 'dashboard'],
    '/collections' => [IndexController::class, 'collections'],
    '/collections/add' => [IndexController::class, 'addCollection'],
    '/view-data' => [IndexController::class, 'viewData'],
    '/input-data' => [IndexController::class, 'inputData'],
    '/search-vector' => [IndexController::class, 'searchVector'],
    '/payload-explorer' => [IndexController::class, 'payloadExplorer'],
    '/embedding-test' => [IndexController::class, 'embeddingTest'],
    '/collection-schema' => [IndexController::class, 'collectionSchema'],
    '/collection-schema/create-index' => [IndexController::class, 'createPayloadIndex'],
    '/collection-schema/delete-index' => [IndexController::class, 'deletePayloadIndex'],
];