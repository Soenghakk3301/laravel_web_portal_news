<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Backend\BannerController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\PhotoGalleryController;
use App\Http\Controllers\Backend\SeoSettingController;
use App\Http\Controllers\Backend\VideoGalleryController;
use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\VideoGallery;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [IndexController::class, 'index']);


Route::middleware(['auht'])->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/dashboard', 'userDashboard')->name('dashboard');
        Route::post('/user/profile/store', 'userProfileStore')->name('user.profile.store');
        Route::get('/user/logout', 'userLogout')->name('user.logout');
        Route::get('/change/password', 'changePassword')->name('change.password');
        Route::post('/user/change/password', 'userChangePassword')->name('user.change.password');
    });
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::controller(AdminController::class)->group(function () {
        Route::get('/admin/dashboard', 'adminDashboard')->name('admin.dashboard');
        Route::get('/admin/logout', 'adminLogout')->name('admin.logout');
        Route::get('/admin/profile', 'adminProfile')->name('admin.profile');
        Route::post('/admin/profile/store', 'adminProfileStore')->name('admin.profile.store');
        Route::get('/admin/change/password', 'adminChangePassword')->name('admin.change.password');
        Route::post('/admin/update/password', 'adminUpdatePassword')->name('admin.update.password');
    });


    // Category Routes
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/all/category', 'allCategory')->name('all.category');
        Route::get('/add/category', 'addCategory')->name('add.category');
        Route::post('/store/category', 'storeCategory')->name('category.store');
        Route::get('/edit/category/{id}', 'editCategory')->name('edit.category');
        Route::post('/update/category', 'updateCategory')->name('category.update');
        Route::get('/delete/category/{id}', 'deleteCategory')->name('delete.category');
    });

    // SubCategory Routes
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/all/subcategory', 'allSubCategory')->name('all.subcategory');
        Route::get('/add/subcategory', 'addSubCategory')->name('add.subcategory');
        Route::post('/store/subcategory', 'storeSubCategory')->name('subcategory.store');
        Route::get('/edit/subcategory/{id}', 'editSubCategory')->name('edit.subcategory');
        Route::post('/update/subcategory', 'updateSubCategory')->name('subcategory.update');
        Route::get('/delete/subcategory/{id}', 'deleteSubCategory')->name('delete.subcategory');
    });


    // Admin User all Route
    Route::controller(AdminController::class)->group(function () {

        Route::get('/all/admin', 'allAdmin')->name('all.admin');
        Route::get('/add/admin', 'addAdmin')->name('add.admin');
        Route::post('/store/admin', 'storeAdmin')->name('admin.store');
        Route::get('/edit/admin/{id}', 'editAdmin')->name('edit.admin');
        Route::post('/update/admin', 'updateAdmin')->name('admin.update');
        Route::get('/delete/admin/{id}', 'deleteAdmin')->name('delete.admin');

        Route::get('/inactive/admin/user/{id}', 'InactiveAdminUser')->name('inactive.admin.user');
        Route::get('/active/admin/user/{id}', 'activeAdminUser')->name('active.admin.user');

    });


    // News Post all Route
    Route::controller(NewsPostController::class)->group(function () {

        Route::get('/all/news/post', 'allNewsPost')->name('all.news.post');
        Route::get('/add/news/post', 'addNewsPost')->name('add.news.post');
        Route::post('/store/news/post', 'storeNewsPost')->name('store.news.post');
        Route::get('/edit/news/post/{id}', 'editNewsPost')->name('edit.news.post');
        Route::post('/update/news/post', 'updateNewsPost')->name('update.news.post');
        Route::get('/delete/news/post/{id}', 'deleteNewsPost')->name('delete.news.post');

        Route::get('/inactive/news/post/{id}', 'inactiveNewsPost')->name('inactive.news.post');
        Route::get('/active/news/post/{id}', 'activeNewsPost')->name('active.news.post');
    });


    // Banner all routes
    Route::controller(BannerController::class)->group(function () {
        Route::get('/all/banners', 'allBanners')->name('all.banners');
        Route::post('/update/banners', 'updateBanners')->name('update.banners');
    });

    // Gallery All Routes
    Route::controller(PhotoGalleryController::class)->group(function () {
        Route::get('/all/photo/gallery', 'allPhotoGallery')->name('all.photo.gallery');
        Route::get('/add/photo/gaery', 'addPhotoGallery')->name('add.photo.gallery');
        Route::post('/store/photo/gallery', 'storePhotoGallery')->name('store.photo.gallery');
        Route::get('/edit/photo/gallery/{id}', 'editPhotoGallery')->name('edit.photo.gallery');
        Route::post('/update/photo/gallery', 'updatePhotoGallery')->name('update.photo.gallery');
        Route::get('/delete/photo/gallery/{id}', 'deletePhotoGallery')->name('delete.photo.gallery');
    });

    Route::controller(VideoGalleryController::class)->group(function () {
        Route::get('/all/video/gallery', 'allVideoGallery')->name('all.video.gallery');
        Route::get('/add/video/gallery', 'addVideoGallery')->name('add.video.gallery');
        Route::post('/store/video/gallery', 'storeVideoGallery')->name('store.video.gallery');
        Route::get('/edit/video/gallery/{id}', 'editVideoGallery')->name('edit.video.gallery');
        Route::post('/update/video/gallery', 'updateVideoGallery')->name('update.video.gallery');
        Route::get('/delete/video/gallery/{id}', 'deleteVideoGallery')->name('delete.video.gallery');

        Route::get('/update/live/tv', 'updateLiveTv')->name('update.live.tv');
    });


    Route::controller(ReviewController::class)->group(function () {

        Route::get('/pending/review', 'pendingReview')->name('pending.review');
        Route::get('/review/approve/{id}', 'ReviewApprove')->name('review.approve');
        Route::get('/approve/review', 'approveReview')->name('approve.review');
        Route::get('/delete/review/{id}', 'deleteReview')->name('delete.review');

    });

    // Review all Route
    Route::controller(SeoSettingController::class)->group(function () {

        Route::get('/seo/setting', 'seoSiteSetting')->name('seo.setting');
        Route::post('/update/seo/setting', 'updateSeoSetting')->name('update.seo.setting');

    });


    // Permission all Route
    Route::controller(RoleController::class)->group(function () {

        Route::get('/all/permission', 'allPermission')->name('all.permission');
        Route::get('/add/permission', 'addPermission')->name('add.permission');
        Route::post('/store/permission', 'storePermission')->name('permission.store');
        Route::get('/edit/permission/{id}', 'editPermission')->name('edit.permission');
        Route::post('/update/permission', 'updatePermission')->name('permission.update');
        Route::get('/delete/permission/{id}', 'deletePermission')->name('delete.permission');
    });


    // Roles all Route
    Route::controller(RoleController::class)->group(function () {

        Route::get('/all/roles', 'allRoles')->name('all.roles');
        Route::get('/add/roles', 'addRoles')->name('add.roles');
        Route::post('/store/roles', 'storeRoles')->name('roles.store');
        Route::get('/edit/roles/{id}', 'editRoles')->name('edit.roles');
        Route::post('/update/roles', 'udateRoles')->name('roles.update');
        Route::get('/delete/roles/{id}', 'deleteRoles')->name('delete.roles');

        Route::get('/add/roles/permission', 'addRolesPermission')->name('add.roles.permission');
        Route::post('/role/permission/store', 'rolePermisssionStore')->name('role.permission.store');
        Route::get('/all/roles/permission', 'allRolesPermission')->name('all.roles.permission');
        Route::get('/admin/edit/roles/{id}', 'adminEditRoles')->name('admin.edit.roles');
        Route::post('/role/permission/update/{id}', 'rolePermissionUpdate')->name('role.permission.update');
        Route::get('/admin/delete/roles/{id}', 'adminDeleteRoles')->name('admin.delete.roles');

    });
});




/// Access for All
Route::get('/news/details/{id}/{slug}', [IndexController::class, 'newsDetails']) ;
Route::get('/news/category/{id}/{slug}', [IndexController::class, 'catWiseNews']);
Route::get('/news/subcategory/{id}/{slug}', [IndexController::class, 'subCatWiseNews']);
Route::get('/lang/change', [IndexController::class, 'Change'])->name('changeLang');
Route::post('/search', [IndexController::class, 'searchByDate'])->name('search-by-date');
Route::post('/news', [IndexController::class, 'newsSearch'])->name('news.search');
Route::get('/reporter/{id}', [IndexController::class, 'reporterNews'])->name('reporter.all.news');
Route::post('/store/review', [ReviewController::class, 'storeReview'])->name('store.review');


Route::get('/admin/login', [AdminController::class, 'adminLogin'])
      ->middleware(RedirectIfAuthenticated::class)
      ->name('admin.login');

Route::get('/admin/logout/page', [AdminController::class, 'adminLogoutPage'])
      ->name('admin.logout.page');

require __DIR__.'/auth.php';