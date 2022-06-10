<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Ajax\PostAjax;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\TestingController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/test', [TestingController::class, 'index'])->name('testing');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('root');

    /**
     * ALL AJAX CONTROLLERS
     */
    //post
    Route::get('/ajax/post/tags', [PostAjax::class, 'getTags']);
    Route::get('/ajax/post/{encryptedPost}/select2tagbypost', [PostAjax::class, 'select2GetTagByPost']);
    Route::patch('/ajax/post/status', [PostAjax::class, 'changeStatus']);
});


Auth::routes();

Route::group(['prefix' => 'filemanager', 'middleware' => ['web', 'auth', 'permission:post create']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

/**
 * PROFILE
 */

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

/**
 * ./PROFILE
 */


/** USER ROUTE */
Route::group(['middleware' => ['auth', 'permission:user read']], function () {
    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::post('/user/datatable', [UserController::class, 'datatable'])->name('user.datatable');
});

Route::post('/user', [UserController::class, 'store'])->name('user.store')->middleware(['auth', 'permission:user create']);

Route::group(['middleware' => ['auth', 'permission:user update']], function () {
    Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::patch('/user/{id}/update', [UserController::class, 'update'])->name('user.update');
});

Route::delete('user/{id}/delete', [UserController::class, 'delete'])->name('user.delete')->middleware(['auth', 'permission:user delete']);


/**
 * ./END USER ROUTE
 */

/**
 * ADMIN
 */

Route::group(['middleware' => ['auth', 'permission:post read']], function () {
    Route::get('/post', [PostController::class, 'index'])->name('post');
    Route::get('/post/create', [PostController::class, 'create'])->name('post.create');
    Route::post('/post/store', [PostController::class, 'store'])->name('post.store');
    Route::get('/post/{id_post}/edit', [PostController::class, 'edit'])->name('post.edit');
    Route::patch('/post/{id_post}/update', [PostController::class, 'update'])->name('post.update');
    Route::delete('/post/{id_post}/delete', [PostController::class, 'delete'])->name('post.delete');


    Route::post('/post/datatable', [PostController::class, 'datatable']);
});




Route::group(['middleware' => ['auth', 'role:super admin']], function () {

    //trash
    Route::get('/user/trash', [UserController::class, 'trash'])->name('user.trash');
    Route::post('/user/datatabletrash', [UserController::class, 'datatableTrash'])->name('user.trashDatatable');
    Route::post('/user/{id}/restore', [UserController::class, 'restore'])->name('user.restore');
    Route::delete('/user/{id}/destroy', [UserController::class, 'destroy'])->name('user.destroy');

    /** ACTIVITIES */
    Route::get('/activity', [ActivityController::class, 'index'])->name('activity');
    Route::post('/activity/datatable', [ActivityController::class, 'datatable'])->name('activity.datatable');
    Route::get('/activity/{activity}/show', [ActivityController::class, 'show'])->name('activity.show');


    Route::get('/rolepermission', [RolePermissionController::class, 'index'])->name('rolepermission');

    /**
     * ROLE PROCESS
     */
    Route::post('/role', [RolePermissionController::class, 'storeRole'])->name('role.store');
    Route::get('/role/{id}/edit', [RolePermissionController::class, 'editRole'])->name('role.edit');
    Route::patch('/role/{id}/update', [RolePermissionController::class, 'updateRole'])->name('role.update');
    Route::delete('/role/{id}/delete', [RolePermissionController::class, 'deleteRole'])->name('role.delete');
    Route::post('/role/datatable', [RolePermissionController::class, 'datatableRoles'])->name('role.datatable');


    /** PERMISSIONS PROCESS */
    Route::post('/permission', [RolePermissionController::class, 'storePermission'])->name('permission.store');
    Route::get('/permission/{id}/edit', [RolePermissionController::class, 'editPermission'])->name('permission.edit');
    Route::patch('/permission/{id}/update', [RolePermissionController::class, 'updatePermission'])->name('permission.update');
    Route::delete('/permission/{id}/delete', [RolePermissionController::class, 'deletePermission'])->name('permission.delete');
    Route::post('/permission/datatable', [RolePermissionController::class, 'datatablePermissions'])->name('permission.datatable');


    /** ASSIGN PROCESS */
    Route::get('/assignpermission', [RolePermissionController::class, 'assign'])->name('assignPermission.assign');
    Route::get('/assignpermission/{id}/viewpermission', [RolePermissionController::class, 'viewPermissions'])->name('assignPermission.viewPermissions');
    Route::post('/assignpermission', [RolePermissionController::class, 'storeAssign'])->name('assignPermission.store');
    Route::post('/assignpermission/datatable', [RolePermissionController::class, 'datatableAssign'])->name('assignPermission.datatable');
});
