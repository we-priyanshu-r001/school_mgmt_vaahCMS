<?php

use VaahCms\Modules\School\Http\Controllers\Backend\TeachersController;

Route::group(
    [
        'prefix' => 'backend/school/teachers',
        
        'middleware' => ['web', 'has.backend.access'],
        
],
function () {
    /**
     * Get Assets
     */
    Route::get('/assets', [TeachersController::class, 'getAssets'])
        ->name('vh.backend.school.teachers.assets');
    /**
     * Get List
     */
    Route::get('/', [TeachersController::class, 'getList'])
        ->name('vh.backend.school.teachers.list');
    /**
     * Update List
     */
    Route::match(['put', 'patch'], '/', [TeachersController::class, 'updateList'])
        ->name('vh.backend.school.teachers.list.update');
    /**
     * Delete List
     */
    Route::delete('/', [TeachersController::class, 'deleteList'])
        ->name('vh.backend.school.teachers.list.delete');


    /**
     * Fill Form Inputs
     */
    Route::any('/fill', [TeachersController::class, 'fillItem'])
        ->name('vh.backend.school.teachers.fill');

    /**
     * Create Item
     */
    Route::post('/', [TeachersController::class, 'createItem'])
        ->name('vh.backend.school.teachers.create');
    /**
     * Get Item
     */
    Route::get('/{id}', [TeachersController::class, 'getItem'])
        ->name('vh.backend.school.teachers.read');
    /**
     * Update Item
     */
    Route::match(['put', 'patch'], '/{id}', [TeachersController::class, 'updateItem'])
        ->name('vh.backend.school.teachers.update');
    /**
     * Delete Item
     */
    Route::delete('/{id}', [TeachersController::class, 'deleteItem'])
        ->name('vh.backend.school.teachers.delete');

    /**
     * List Actions
     */
    Route::any('/action/{action}', [TeachersController::class, 'listAction'])
        ->name('vh.backend.school.teachers.list.actions');

    /**
     * Item actions
     */
    Route::any('/{id}/action/{action}', [TeachersController::class, 'itemAction'])
        ->name('vh.backend.school.teachers.item.action');

    //---------------------------------------------------------

});
