<?php

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\AbsensiUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KantorZoneController;
use App\Http\Controllers\KuponController;
use App\Http\Controllers\NavigationGroupController;
use App\Http\Controllers\NavigationMenuController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:api')->group(function () {
    Route::get('user-navigation', [UserController::class, 'getUserMenu']);
    Route::get('user', [UserController::class, 'index'])->middleware('cekRole:User,read');
    Route::get('user-detail/{id}', [UserController::class, 'show'])->middleware('cekRole:User,read');

    Route::get('profile', [DashboardController::class, 'profile']);
    Route::get('dashboard-admin', [DashboardController::class, 'dashboardAdmin'])->middleware('cekRole:DashboardAdmin,read');

    // ROUTE CATEGORY
    Route::get('category', [CategoryController::class, 'index'])->middleware('cekRole:Category,read');
    Route::get('category/{search}', [CategoryController::class, 'search'])->middleware('cekRole:Category,read');
    Route::get('category-detail/{id}', [CategoryController::class, 'show'])->middleware('cekRole:Category,read');
    Route::post('category-create', [CategoryController::class, 'store'])->middleware('cekRole:Category,create');
    Route::put('category-update/{id}', [CategoryController::class, 'update'])->middleware('cekRole:Category,update');
    Route::delete('category-delete/{id}', [CategoryController::class, 'destroy'])->middleware('cekRole:Category,delete');

    // ROUTE ROLE
    Route::get('role', [RoleController::class, 'index'])->middleware('cekRole:Role,read');
    Route::get('role-detail/{id}', [RoleController::class, 'show'])->middleware('cekRole:Role,read');
    Route::get('role/{search}', [RoleController::class, 'search'])->middleware('cekRole:Role,read');
    Route::post('role-create', [RoleController::class, 'store'])->middleware('cekRole:Role,create');
    Route::put('role-update/{id}', [RoleController::class, 'update'])->middleware('cekRole:Role,update');
    Route::delete('role-delete/{id}', [RoleController::class, 'destroy'])->middleware('cekRole:Role,delete');


    // ROUTE NAVIGATION MENU
    Route::get('navigationMenu', [NavigationMenuController::class, 'index'])->middleware('cekRole:NavigationMenu,read');
    Route::get('navigationMenu-detail/{id}', [NavigationMenuController::class, 'show'])->middleware('cekRole:NavigationMenu,read');
    Route::get('navigationMenu/{search}', [NavigationMenuController::class, 'search'])->middleware('cekRole:NavigationMenu,read');

    // ROUTE NAVIGATION GROUP
    Route::get('navigationGroup', [NavigationGroupController::class, 'index'])->middleware('cekRole:NavigationGroup,read');
    Route::get('navigationGroup-detail/{id}', [NavigationGroupController::class, 'show'])->middleware('cekRole:NavigationGroup,read');
    Route::get('navigationGroup/{search}', [NavigationGroupController::class, 'search'])->middleware('cekRole:NavigationGroup,read');
    Route::post('navigationGroup-create', [NavigationGroupController::class, 'store'])->middleware('cekRole:NavigationGroup,create');
    Route::put('navigationGroup-update/{id}', [NavigationGroupController::class, 'update'])->middleware('cekRole:NavigationGroup,update');
    Route::delete('navigationGroup-delete/{id}', [NavigationGroupController::class, 'destroy'])->middleware('cekRole:NavigationGroup,delete');

    // ROUTE KUPON
    Route::get('kupon', [KuponController::class, 'index'])->middleware('cekRole:Kupon,read');
    Route::get('kupon-detail/{id}', [KuponController::class, 'show'])->middleware('cekRole:Kupon,read');
    Route::get('kupon/{search}', [KuponController::class, 'search'])->middleware('cekRole:Kupon,read');
    Route::post('kupon-create', [KuponController::class, 'store'])->middleware('cekRole:Kupon,create');
    Route::put('kupon-update/{id}', [KuponController::class, 'update'])->middleware('cekRole:Kupon,update');
    Route::delete('kupon-delete/{id}', [KuponController::class, 'destroy'])->middleware('cekRole:Kupon,delete');

    // ROUTE ABSENSI ADMIN
    Route::get('absensi', [AbsenController::class, 'index'])->middleware('cekRole:Absensi,read');
    Route::get('absensi-detail/{id}', [AbsenController::class, 'show'])->middleware('cekRole:Absensi,read');
    Route::get('absensi/{search}', [AbsenController::class, 'search'])->middleware('cekRole:Absensi,read');
    Route::get('absensi-confirm/{id}', [AbsenController::class, 'confirm'])->middleware('cekRole:Absensi,read');

    // ROUTE ABSENSI PERSONAL
    Route::get('absensi-personal', [AbsensiUserController::class, 'index'])->middleware('cekRole:AbsensiPersonal,read');
    Route::get('absensi-personal/detail/{id}', [AbsensiUserController::class, 'show'])->middleware('cekRole:AbsensiPersonal,read');
    Route::get('listIzin', [AbsensiUserController::class, 'listIzin'])->middleware('cekRole:AbsensiPersonal,read');
    Route::get('listIzin/{search}', [AbsensiUserController::class, 'searchIzin'])->middleware('cekRole:AbsensiPersonal,read');
    Route::post('izin-create/{id}', [AbsensiUserController::class, 'izin'])->middleware('cekRole:AbsensiPersonal,create');
    Route::post('absensi-create', [AbsensiUserController::class, 'store'])->middleware('cekRole:AbsensiPersonal,create');
    Route::put('absensi-update/{id}', [AbsensiUserController::class, 'update'])->middleware('cekRole:AbsensiPersonal,update');
    Route::delete('absensi-delete/{id}', [AbsensiUserController::class, 'destroy'])->middleware('cekRole:AbsensiPersonal,delete');

    Route::get('zona', [KantorZoneController::class, 'getZoneForUser'])->middleware('cekRole:ZonaForUser,read');
    // ROUTE KANTOR ZONE
    Route::get('kantorZone', [KantorZoneController::class, 'index'])->middleware('cekRole:KantorZone,read');
    Route::get('kantorZone-detail/{id}', [KantorZoneController::class, 'show'])->middleware('cekRole:KantorZone,read');
    Route::get('kantorZone/{search}', [KantorZoneController::class, 'search'])->middleware('cekRole:KantorZone,read');
    Route::post('kantorZone-create', [KantorZoneController::class, 'store'])->middleware('cekRole:KantorZone,create');
    Route::put('kantorZone-update/{id}', [KantorZoneController::class, 'update'])->middleware('cekRole:KantorZone,update');
    Route::delete('kantorZone-delete/{id}', [KantorZoneController::class, 'destroy'])->middleware('cekRole:KantorZone,delete');
});

Route::post('login', [AuthController::class, 'login']);
