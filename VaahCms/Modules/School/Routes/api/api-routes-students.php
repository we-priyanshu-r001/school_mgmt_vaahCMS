<?php
use VaahCms\Modules\School\Http\Controllers\Backend\StudentsController;
/*
 * API url will be: <base-url>/public/api/school/students
 */
Route::group(
    [
        'prefix' => 'school/students',
        'namespace' => 'Backend',
    ],
function () {

    /**
     * Get Assets
     */
    Route::get('/assets', [StudentsController::class, 'getAssets'])
        ->name('vh.backend.school.api.students.assets');
    /**
     * Get List
     */
    Route::get('/', [StudentsController::class, 'getList'])
        ->name('vh.backend.school.api.students.list');
    /**
     * Update List
     */
    Route::match(['put', 'patch'], '/', [StudentsController::class, 'updateList'])
        ->name('vh.backend.school.api.students.list.update');
    /**
     * Delete List
     */
    Route::delete('/', [StudentsController::class, 'deleteList'])
        ->name('vh.backend.school.api.students.list.delete');


    /**
     * Create Item
     */
    Route::post('/', [StudentsController::class, 'createItem'])
        ->name('vh.backend.school.api.students.create');
    /**
     * Get Item
     */
    Route::get('/{id}', [StudentsController::class, 'getItem'])
        ->name('vh.backend.school.api.students.read');
    /**
     * Update Item
     */
    Route::match(['put', 'patch'], '/{id}', [StudentsController::class, 'updateItem'])
        ->name('vh.backend.school.api.students.update');
    /**
     * Delete Item
     */
    Route::delete('/{id}', [StudentsController::class, 'deleteItem'])
        ->name('vh.backend.school.api.students.delete');

    /**
     * List Actions
     */
    Route::any('/action/{action}', [StudentsController::class, 'listAction'])
        ->name('vh.backend.school.api.students.list.action');

    /**
     * Item actions
     */
    Route::any('/{id}/action/{action}', [StudentsController::class, 'itemAction'])
        ->name('vh.backend.school.api.students.item.action');



});
