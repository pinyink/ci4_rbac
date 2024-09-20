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
$routes->get('/setting/profil', 'Profil::index', ['filter' => 'auth:Y', 'namespace' => 'App\Controllers\Setting']);
$routes->get('/setting/profil/getData', 'Profil::getData', ['filter' => 'auth:N' , 'namespace' => 'App\Controllers\Setting']);
$routes->post('/setting/profil/update', 'Profil::update', ['filter' => 'auth:N', 'namespace' => 'App\Controllers\Setting']);
$routes->post('/setting/profil/updateFoto', 'Profil::updateFoto', ['filter' => 'auth:N', 'namespace' => 'App\Controllers\Setting']);
$routes->post('/setting/profil/updatePassword', 'Profil::updatePassword', ['filter' => 'auth:N', 'namespace' => 'App\Controllers\Setting']);

$routes->group('crud', ['namespace' => 'App\Controllers', 'filter' => 'admin'], static function($routes) {
    $routes->get('/', 'CrudController::index');
    $routes->post('table', 'CrudController::table');
    $routes->post('result', 'CrudController::result');
});

$routes->group('/crid', ['namespace' => 'App\Controllers', 'filter' => 'admin'], static function($routes) {
    $routes->get('/', 'CridController::index');
    $routes->post('ajax_list', 'CridController::ajaxList');
    $routes->post('save_data', 'CridController::saveData');
    $routes->post('update_data', 'CridController::saveData');
    $routes->get('(:num)/get_data', 'CridController::getData/$1');
    $routes->delete('(:num)/delete_data', 'CridController::deleteData/$1');
	$routes->post('table_exist', 'CridController::tableExist');

    $routes->get('(:num)/generate_crud', 'CridController::generateCrud/$1');
});

$routes->group('/criddetail', ['namespace' => 'App\Controllers'], static function($routes) {
    $routes->get('/', 'CridDetailController::index', ['filter' => 'admin']);
    $routes->post('ajax_list', 'CridDetailController::ajaxList', ['filter' => 'admin']);
    $routes->post('save_data', 'CridDetailController::saveData', ['filter' => 'admin']);
    $routes->post('update_data', 'CridDetailController::saveData', ['filter' => 'admin']);
    $routes->get('(:num)/get_data', 'CridDetailController::getData/$1', ['filter' => 'admin']);
    $routes->delete('(:num)/delete_data', 'CridDetailController::deleteData/$1', ['filter' => 'admin']);
	$routes->post('name_field_exist', 'CridDetailController::namefieldExist', ['filter' => 'admin']);
    $routes->get('(:num)/by_crid_id', 'CridDetailController::byCridId/$1', ['filter' => 'admin']);
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

$routes->group('/admin/policy', ['namespace' => 'App\Controllers\Admin'], static function($routes) {
    $routes->get('/', 'PolicyController::index', ['filter' => 'admin']);
    $routes->post('ajax_list', 'PolicyController::ajaxList', ['filter' => 'adminjson']);
    $routes->post('save_data', 'PolicyController::saveData', ['filter' => 'adminjson']);
    $routes->post('update_data', 'PolicyController::saveData', ['filter' => 'adminjson']);
    $routes->get('(:any)/get_data', 'PolicyController::getData/$1', ['filter' => 'adminjson']);
    $routes->delete('(:any)/delete_data', 'PolicyController::deleteData/$1', ['filter' => 'adminjson']);
	$routes->post('policy_id_exist', 'PolicyController::policyidExist', ['filter' => 'adminjson']);
    $routes->get('menuList/(:any)', 'PolicyController::menuList/$1', ['filter' => 'adminjson']);
    $routes->post('saveSubMenu', 'PolicyController::saveSubMenu', ['filter' => 'adminjson', 'namespace' => 'App\Controllers\Admin']);
    $routes->post('addPolicy', 'PolicyController::addPolicy', ['filter' => 'adminjson']);
    $routes->post('removePolicy', 'PolicyController::removePolicy', ['filter' => 'adminjson']);
});

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

$routes->group('statistic', ['namespace' => 'App\Controllers'], static function($routes) {
    $routes->get('/', 'StatisticController::index', ['filter' => 'admin']);
    $routes->post('permonth', 'StatisticController::permonth', ['filter' => 'admin']);
});
/**
 * Menu Content
 */
$routes->get('/menu_dua', 'Menu_dua::index', ['namespace' => 'App\Controllers']);
$routes->get('/menu_dua_db', 'Menu_dua::createDb', ['namespace' => 'App\Controllers']);
$routes->get('/menu_satu', 'Menu_satu::index', ['filter' => 'auth:N, 1, 1', 'namespace' => 'App\Controllers']);

$routes->group('/siswa', ['namespace' => 'App\Controllers'], static function($routes) {
    $routes->get('/', 'SiswaController::index', ['filter' => 'auth:Y,1,1']);
    $routes->post('ajax_list', 'SiswaController::ajaxList', ['filter' => 'auth:N,1,1']);
    $routes->post('save_data', 'SiswaController::saveData', ['filter' => 'auth:N,1,2']);
    $routes->post('update_data', 'SiswaController::saveData', ['filter' => 'auth:N,1,3']);
    $routes->get('(:num)/get_data', 'SiswaController::getData/$1', ['filter' => 'auth:N,1,1']);
    $routes->delete('(:num)/delete_data', 'SiswaController::deleteData/$1', ['filter' => 'auth:N,1,4']);
	$routes->post('siswa_nama_exist', 'SiswaController::siswanamaExist', ['filter' => 'auth:N,1,2']);
});

// test crid
$routes->group('product', ['namespace' => 'App\Controllers'], static function($routes) {
    $routes->get('index', 'ProductController::index', ['filter' => 'auth:Y,1,1']);
    $routes->post('ajax_list', 'ProductController::ajaxList', ['filter' => 'auth:N,1,1']);
    $routes->get('tambah', 'ProductController::tambahData', ['filter' => 'auth:N,1,2']);
    $routes->post('save', 'ProductController::saveData', ['filter' => 'auth:N,1,2']);
    $routes->get('(:num)/detail', 'ProductController::detailData/$1', ['filter' => 'auth:Y,1,1']);
    $routes->get('(:num)/edit', 'ProductController::editData/$1', ['filter' => 'auth:N,1,3']);
    $routes->post('update', 'ProductController::saveData', ['filter' => 'auth:N,1,3']);
    $routes->delete('(:num)/delete', 'ProductController::deleteData/$1', ['filter' => 'auth:N,1,4']);
});

$routes->group('product_categories', ['namespace' => 'App\Controllers'], static function($routes) {
    $routes->get('index', 'ProductCategoriesController::index', ['filter' => 'auth:Y,1,1']);
    $routes->post('ajax_list', 'ProductCategoriesController::ajaxList', ['filter' => 'auth:N,1,1']);
    $routes->get('tambah', 'ProductCategoriesController::tambahData', ['filter' => 'auth:N,1,2']);
    $routes->post('save', 'ProductCategoriesController::saveData', ['filter' => 'auth:N,1,2']);
    $routes->get('(:num)/detail', 'ProductCategoriesController::detailData/$1', ['filter' => 'auth:Y,1,1']);
    $routes->get('(:num)/edit', 'ProductCategoriesController::editData/$1', ['filter' => 'auth:N,1,3']);
    $routes->post('update', 'ProductCategoriesController::saveData', ['filter' => 'auth:N,1,3']);
    $routes->delete('(:num)/delete', 'ProductCategoriesController::deleteData/$1', ['filter' => 'auth:N,1,4']);
});