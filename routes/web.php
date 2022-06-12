<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Ajax\PageAjax;
use App\Http\Controllers\Ajax\PostAjax;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Master\PageController;
use App\Http\Controllers\Post\CategoryController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Post\TagController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\TestingController;
use App\Http\Controllers\UserController;
use App\Models\Post\Category;

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
    Route::get('/ajax/post/{string}/createcatslug', [PostAjax::class, 'createCatSlug']);
    Route::get('/ajax/post/{string}/createtagslug', [PostAjax::class, 'createTagSlug']);
    Route::get('/ajax/post/{id_category}/cekcatpost', [PostAjax::class, 'cekCatPost']);
    Route::get('/ajax/post/getcats', [PostAjax::class, 'getCats']);
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

/**
 * POST MODUL
 */

//post
Route::group(['middleware' => ['auth', 'permission:post read']], function () {
    Route::get('/post', [PostController::class, 'index'])->name('post');
    Route::post('/post/datatable', [PostController::class, 'datatable']);
});

Route::group(['middleware' => ['auth', 'permission:post create']], function () {
    Route::get('/post/create', [PostController::class, 'create'])->name('post.create');
    Route::post('/post/store', [PostController::class, 'store'])->name('post.store');
});

Route::group(['middleware' => ['auth', 'permission:post update']], function () {
    Route::get('/post/{id_post}/edit', [PostController::class, 'edit'])->name('post.edit');
    Route::patch('/post/{id_post}/update', [PostController::class, 'update'])->name('post.update');
    Route::patch('/ajax/post/status', [PostAjax::class, 'changeStatus']);
    Route::patch('/post/{id_category}/bulksetcat', [PostController::class, 'bulkSetCat'])->name('post.bulkSetCat');
});

Route::delete('/post/{id_post}/delete', [PostController::class, 'delete'])
    ->name('post.delete')->middleware(['auth', 'permission:post delete']);

//category
Route::group(['middleware' => ['auth', 'permission:category read']], function () {
    Route::get('/category', [CategoryController::class, 'index'])->name('category');
    Route::post('/category/datatable', [CategoryController::class, 'datatable']);
});

Route::group(['middleware' => ['auth', 'permission:category create']], function () {
    Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
});

Route::group(['middleware' => ['auth', 'permission:category update']], function () {
    Route::get('/category/{id_category}/edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::patch('/category/{id_category}/update', [CategoryController::class, 'update'])->name('category.update');
});

Route::group(['middleware' => ['auth', 'permission:category delete']], function () {

    Route::delete('/category/{id_category}/destroy', [CategoryController::class, 'destroy'])->name('category.destroy');
});

//tags
Route::group(['middleware' => ['auth', 'permission:tag read']], function () {
    // Route::get('/tag', [TagController::class, 'index'])->name('tag');
    Route::post('/tag/datatable', [TagController::class, 'datatable']);
});

Route::group(['middleware' => ['auth', 'permission:tag create']], function () {
    Route::post('/tag', [TagController::class, 'store'])->name('tag.store');
});

Route::group(['middleware' => ['auth', 'permission:tag update']], function () {
    Route::get('/tag/{id_tag}/edit', [TagController::class, 'edit'])->name('tag.edit');
    Route::patch('/tag/{id_tag}/update', [TagController::class, 'update'])->name('tag.update');
});

Route::group(['middleware' => ['auth', 'permission:tag delete']], function () {

    Route::delete('/tag/{id_tag}/destroy', [TagController::class, 'destroy'])->name('tag.destroy');
});


/**
 * ./END POST MODUL
 */

/**
 * MODUL PAGES
 */

Route::group(['middleware' => ['auth', 'permission:page create']], function () {
    Route::get('/page/create', [PageController::class, 'create'])->name('page.create');
    Route::post('/page/store', [PageController::class, 'store'])->name('page.store');
});

Route::group(['middleware' => ['auth', 'permission:page read']], function () {
    Route::get('/page', [PageController::class, 'index'])->name('page');
    Route::post('/page/datatable', [PageController::class, 'datatable']);
});



Route::group(['middleware' => ['auth', 'permission:page update']], function () {
    Route::get('/page/{id_post}/edit', [PageController::class, 'edit'])->name('page.edit');
    Route::patch('/page/{id_post}/update', [PageController::class, 'update'])->name('page.update');
    Route::patch('/ajax/page/status', [PageAjax::class, 'changeStatus']);
});

Route::group(['middleware' => ['auth', 'permission:page delete']], function () {
    Route::delete('/page/{id_post}/delete', [PageController::class, 'delete'])->name('page.delete');
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
