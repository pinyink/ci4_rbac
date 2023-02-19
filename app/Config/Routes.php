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
$routes->group('/admin/user', ['namespace' => 'App\Controllers\Admin'], static function($routes) {
    $routes->get('/', 'UserController::index', ['filter' => 'admin']);
    $routes->post('ajax_list', 'UserController::ajaxList', ['filter' => 'adminjson']);
    $routes->post('save_data', 'UserController::saveData', ['filter' => 'adminjson']);
    $routes->get('get_data/(:num)', 'UserController::getData/$1', ['filter' => 'adminjson']);
    $routes->delete('delete_data/(:num)', 'UserController::deleteData/$1', ['filter' => 'adminjson']);
	$routes->post('user_username_exist', 'UserController::userusernameExist', ['filter' => 'adminjson']);
});

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

$routes->group('/admin/menu', ['namespace' => 'App\Controllers\Admin'], static function($routes) {
    $routes->get('/', 'MenuController::index', ['filter' => 'admin']);
    $routes->post('ajax_list', 'MenuController::ajaxList', ['filter' => 'adminjson']);
    $routes->post('save_data', 'MenuController::saveData', ['filter' => 'adminjson']);
    $routes->get('get_data/(:num)', 'MenuController::getData/$1', ['filter' => 'adminjson']);
    $routes->delete('delete_data/(:num)', 'MenuController::deleteData/$1', ['filter' => 'adminjson']);
	$routes->post('menu_desc_exist', 'MenuController::menudescExist', ['filter' => 'adminjson']);
});
$routes->group('/admin/menuakses', ['namespace' => 'App\Controllers\Admin'], static function($routes) {
    $routes->post('save_data', 'MenuAksesController::saveData', ['filter' => 'adminjson']);
    $routes->get('get_data/(:num)', 'MenuAksesController::getData/$1', ['filter' => 'adminjson']);
    $routes->delete('delete_data/(:num)', 'MenuAksesController::deleteData/$1', ['filter' => 'adminjson']);
});
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
