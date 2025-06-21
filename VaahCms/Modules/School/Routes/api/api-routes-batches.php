<?php
use VaahCms\Modules\School\Http\Controllers\Backend\BatchesController;
/*
 * API url will be: <base-url>/public/api/school/batches
 */
Route::group(
    [
        'prefix' => 'school/batches',
        'namespace' => 'Backend',
    ],
function () {

    /**
     * Get Assets
     */
    Route::get('/assets', [BatchesController::class, 'getAssets'])
        ->name('vh.backend.school.api.batches.assets');
    /**
     * Get List
     */
    Route::get('/', [BatchesController::class, 'getList'])
        ->name('vh.backend.school.api.batches.list');
    /**
     * Update List
     */
    Route::match(['put', 'patch'], '/', [BatchesController::class, 'updateList'])
        ->name('vh.backend.school.api.batches.list.update');
    /**
     * Delete List
     */
    Route::delete('/', [BatchesController::class, 'deleteList'])
        ->name('vh.backend.school.api.batches.list.delete');


    /**
     * Create Item
     */
    Route::post('/', [BatchesController::class, 'createItem'])
        ->name('vh.backend.school.api.batches.create');
    /**
     * Get Item
     */
    Route::get('/{id}', [BatchesController::class, 'getItem'])
        ->name('vh.backend.school.api.batches.read');
    /**
     * Update Item
     */
    Route::match(['put', 'patch'], '/{id}', [BatchesController::class, 'updateItem'])
        ->name('vh.backend.school.api.batches.update');
    /**
     * Delete Item
     */
    Route::delete('/{id}', [BatchesController::class, 'deleteItem'])
        ->name('vh.backend.school.api.batches.delete');

    /**
     * List Actions
     */
    Route::any('/action/{action}', [BatchesController::class, 'listAction'])
        ->name('vh.backend.school.api.batches.list.action');

    /**
     * Item actions
     */
    Route::any('/{id}/action/{action}', [BatchesController::class, 'itemAction'])
        ->name('vh.backend.school.api.batches.item.action');



});
