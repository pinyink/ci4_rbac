<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index', ['filter' => 'auth:Y']);
$routes->get('/home', 'Home::index', ['filter' => 'auth:Y']);
$routes->get('/login', 'Login::index');
$routes->get('/logout', 'Login::logout');
$routes->post('auth', 'Login::loginAksi');
$routes->get('/setting', 'SettingController::index', ['filter' => 'admin', 'namespace' => 'App\Controllers']);
$routes->get('/setting/profil', 'Profil::index', ['filter' => 'auth:N', 'namespace' => 'App\Controllers\Setting']);
$routes->get('/setting/profil/getData', 'Profil::getData', ['filter' => 'auth:N' , 'namespace' => 'App\Controllers\Setting']);
$routes->post('/setting/profil/update', 'Profil::update', ['filter' => 'auth:N', 'namespace' => 'App\Controllers\Setting']);
$routes->post('/setting/profil/updateFoto', 'Profil::updateFoto', ['filter' => 'auth:N', 'namespace' => 'App\Controllers\Setting']);
$routes->post('/setting/profil/updatePassword', 'Profil::updatePassword', ['filter' => 'auth:N', 'namespace' => 'App\Controllers\Setting']);

$routes->group('crud', ['namespace' => 'App\Controllers', 'filter' => 'admin'], static function($routes) {
    $routes->get('/', 'CrudController::index');
    $routes->post('table', 'CrudController::table');
    $routes->post('result', 'CrudController::result');
});

/**
 * Admin
 */
$routes->get('admin/user', 'User::index', ['filter' => 'admin', 'namespace' => 'App\Controllers\Admin']);
$routes->post('admin/user/ajaxList', 'User::ajaxList', ['filter' => 'adminjson', 'namespace' => 'App\Controllers\Admin']);
$routes->post('admin/user/resetPassword', 'User::resetPassword', ['filter' => 'adminjson', 'namespace' => 'App\Controllers\Admin']);
$routes->post('admin/user/nonAktifUser', 'User::nonAktifUser', ['filter' => 'adminjson', 'namespace' => 'App\Controllers\Admin']);
$routes->post('admin/user/saveData', 'User::saveData', ['filter' => 'adminjson', 'namespace' => 'App\Controllers\Admin']);
$routes->get('admin/policy', 'Policy::index', ['filter' => 'admin', 'namespace' => 'App\Controllers\Admin']);
$routes->post('admin/policy/ajaxList', 'Policy::ajaxList', ['filter' => 'adminjson', 'namespace' => 'App\Controllers\Admin']);
$routes->post('admin/policy/saveData', 'Policy::saveData', ['filter' => 'adminjson', 'namespace' => 'App\Controllers\Admin']);
$routes->put('admin/policy/getData/(:num)/', 'Policy::getData/$1', ['filter' => 'adminjson', 'namespace' => 'App\Controllers\Admin']);
$routes->post('admin/policy/updateData', 'Policy::updateData', ['filter' => 'adminjson', 'namespace' => 'App\Controllers\Admin']);
$routes->post('admin/policy/userList', 'Policy::userList', ['filter' => 'adminjson', 'namespace' => 'App\Controllers\Admin']);
$routes->post('admin/policy/addRole', 'Policy::addRole', ['filter' => 'adminjson', 'namespace' => 'App\Controllers\Admin']);
$routes->post('admin/policy/removeRole', 'Policy::removeRole', ['filter' => 'adminjson', 'namespace' => 'App\Controllers\Admin']);
$routes->get('admin/policy/menuList/(:num)', 'Policy::menuList/$1', ['filter' => 'adminjson', 'namespace' => 'App\Controllers\Admin']);
$routes->post('admin/policy/saveSubMenu', 'Policy::saveSubMenu', ['filter' => 'adminjson', 'namespace' => 'App\Controllers\Admin']);
$routes->post('admin/policy/addPolicy', 'Policy::addPolicy', ['filter' => 'adminjson', 'namespace' => 'App\Controllers\Admin']);
$routes->post('admin/policy/removePolicy', 'Policy::removePolicy', ['filter' => 'adminjson', 'namespace' => 'App\Controllers\Admin']);
$routes->get('admin/menu', 'Menu::index', ['filter' => 'adminjson', 'namespace' => 'App\Controllers\Admin']);
$routes->post('admin/menu/saveMenu', 'Menu::saveMenu', ['filter' => 'adminjson', 'namespace' => 'App\Controllers\Admin']);
$routes->get('admin/menu/getMenu/(:num)', 'Menu::getMenu/$1', ['filter' => 'adminjson', 'namespace' => 'App\Controllers\Admin']);
$routes->post('admin/menu/updateMenu', 'Menu::updateMenu', ['filter' => 'adminjson', 'namespace' => 'App\Controllers\Admin']);
$routes->get('admin/menu/getMenuAkses/(:num)', 'Menu::getMenuAkses/$1', ['filter' => 'adminjson', 'namespace' => 'App\Controllers\Admin']);
$routes->post('admin/menu/updateMenuAkses', 'Menu::updateMenuAkses', ['filter' => 'adminjson', 'namespace' => 'App\Controllers\Admin']);
$routes->post('admin/menu/tambahMenuAkses', 'Menu::tambahMenuAkses', ['filter' => 'adminjson', 'namespace' => 'App\Controllers\Admin']);
/**
 * Menu Content
 */
$routes->get('/menu_dua', 'Menu_dua::index', ['filter' => 'auth:N, 2, 1', 'namespace' => 'App\Controllers']);
$routes->get('/menu_satu', 'Menu_satu::index', ['filter' => 'auth:N, 1, 1', 'namespace' => 'App\Controllers']);

$routes->group('/master/masteropd', ['namespace' => 'App\Controllers\Master'], static function($routes) {
    $routes->get('/', 'MasterOpdController::index', ['filter' => 'auth:Y,1,1']);
    $routes->post('ajax_list', 'MasterOpdController::ajaxList', ['filter' => 'auth:N,1,1']);
    $routes->post('save_data', 'MasterOpdController::saveData', ['filter' => 'auth:N,1,2']);
    $routes->get('get_data/(:num)', 'MasterOpdController::getData/$1', ['filter' => 'auth:N,1,2']);
    $routes->delete('delete_data/(:num)', 'MasterOpdController::deleteData/$1', ['filter' => 'auth:N,1,3']);
	$routes->post('opd_exist', 'MasterOpdController::opdExist', ['filter' => 'auth:N,1,2']);
});

$routes->group('/master/desa', ['namespace' => 'App\Controllers\Master'], static function($routes) {
    $routes->get('/', 'DesaController::index', ['filter' => 'auth:Y,2,1']);
    $routes->post('ajax_list', 'DesaController::ajaxList', ['filter' => 'auth:N,2,1']);
    $routes->post('save_data', 'DesaController::saveData', ['filter' => 'auth:N,2,2']);
    $routes->get('get_data/(:num)', 'DesaController::getData/$1', ['filter' => 'auth:N,2,2']);
    $routes->delete('delete_data/(:num)', 'DesaController::deleteData/$1', ['filter' => 'auth:N,2,3']);
});
