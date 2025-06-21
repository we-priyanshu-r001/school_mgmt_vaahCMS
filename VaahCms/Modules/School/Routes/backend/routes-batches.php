<?php

use VaahCms\Modules\School\Http\Controllers\Backend\BatchesController;

Route::group(
    [
        'prefix' => 'backend/school/batches',
        
        'middleware' => ['web', 'has.backend.access'],
        
],
function () {
    /**
     * Get Assets
     */
    Route::get('/assets', [BatchesController::class, 'getAssets'])
        ->name('vh.backend.school.batches.assets');
    /**
     * Get List
     */
    Route::get('/', [BatchesController::class, 'getList'])
        ->name('vh.backend.school.batches.list');
    /**
     * Update List
     */
    Route::match(['put', 'patch'], '/', [BatchesController::class, 'updateList'])
        ->name('vh.backend.school.batches.list.update');
    /**
     * Delete List
     */
    Route::delete('/', [BatchesController::class, 'deleteList'])
        ->name('vh.backend.school.batches.list.delete');


    /**
     * Fill Form Inputs
     */
    Route::any('/fill', [BatchesController::class, 'fillItem'])
        ->name('vh.backend.school.batches.fill');

    /**
     * Create Item
     */
    Route::post('/', [BatchesController::class, 'createItem'])
        ->name('vh.backend.school.batches.create');
    /**
     * Get Item
     */
    Route::get('/{id}', [BatchesController::class, 'getItem'])
        ->name('vh.backend.school.batches.read');
    /**
     * Update Item
     */
    Route::match(['put', 'patch'], '/{id}', [BatchesController::class, 'updateItem'])
        ->name('vh.backend.school.batches.update');
    /**
     * Delete Item
     */
    Route::delete('/{id}', [BatchesController::class, 'deleteItem'])
        ->name('vh.backend.school.batches.delete');

    /**
     * List Actions
     */
    Route::any('/action/{action}', [BatchesController::class, 'listAction'])
        ->name('vh.backend.school.batches.list.actions');

    /**
     * Item actions
     */
    Route::any('/{id}/action/{action}', [BatchesController::class, 'itemAction'])
        ->name('vh.backend.school.batches.item.action');

    //---------------------------------------------------------

});
