<?php

use VaahCms\Modules\School\Http\Controllers\Backend\StudentsController;

Route::group(
    [
        'prefix' => 'backend/school/students',
        
        'middleware' => ['web', 'has.backend.access'],
        
],
function () {
    /**
     * Get Assets
     */
    Route::get('/assets', [StudentsController::class, 'getAssets'])
        ->name('vh.backend.school.students.assets');
    /**
     * Get List
     */
    Route::get('/', [StudentsController::class, 'getList'])
        ->name('vh.backend.school.students.list');
    /**
     * Update List
     */
    Route::match(['put', 'patch'], '/', [StudentsController::class, 'updateList'])
        ->name('vh.backend.school.students.list.update');
    /**
     * Delete List
     */
    Route::delete('/', [StudentsController::class, 'deleteList'])
        ->name('vh.backend.school.students.list.delete');


    /**
     * Fill Form Inputs
     */
    Route::any('/fill', [StudentsController::class, 'fillItem'])
        ->name('vh.backend.school.students.fill');

    /**
     * Create Item
     */
    Route::post('/', [StudentsController::class, 'createItem'])
        ->name('vh.backend.school.students.create');
    /**
     * Get Item
     */
    Route::get('/{id}', [StudentsController::class, 'getItem'])
        ->name('vh.backend.school.students.read');
    /**
     * Update Item
     */
    Route::match(['put', 'patch'], '/{id}', [StudentsController::class, 'updateItem'])
        ->name('vh.backend.school.students.update');
    /**
     * Delete Item
     */
    Route::delete('/{id}', [StudentsController::class, 'deleteItem'])
        ->name('vh.backend.school.students.delete');

    /**
     * List Actions
     */
    Route::any('/action/{action}', [StudentsController::class, 'listAction'])
        ->name('vh.backend.school.students.list.actions');

    /**
     * Item actions
     */
    Route::any('/{id}/action/{action}', [StudentsController::class, 'itemAction'])
        ->name('vh.backend.school.students.item.action');

    //---------------------------------------------------------

});
