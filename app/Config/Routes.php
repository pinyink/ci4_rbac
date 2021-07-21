<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index', ['filter' => 'auth:N']);
$routes->get('/home', 'Home::index', ['filter' => 'auth:N']);
$routes->get('/login', 'Login::index');
$routes->get('/logout', 'Login::logout');
$routes->post('auth', 'Login::loginAksi');
$routes->get('/setting/profil', 'Profil::index', ['filter' => 'auth:N', 'namespace' => 'App\Controllers\Setting']);
$routes->get('/setting/profil/getData', 'Profil::getData', ['filter' => 'auth:N' , 'namespace' => 'App\Controllers\Setting']);
$routes->post('/setting/profil/update', 'Profil::update', ['filter' => 'auth:N', 'namespace' => 'App\Controllers\Setting']);
$routes->post('/setting/profil/updateFoto', 'Profil::updateFoto', ['filter' => 'auth:N', 'namespace' => 'App\Controllers\Setting']);

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

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
$routes->post('admin/policy/menuList', 'Policy::menuList', ['filter' => 'adminjson', 'namespace' => 'App\Controllers\Admin']);
$routes->post('admin/policy/addPolicy', 'Policy::addPolicy', ['filter' => 'adminjson', 'namespace' => 'App\Controllers\Admin']);
$routes->post('admin/policy/removePolicy', 'Policy::removePolicy', ['filter' => 'adminjson', 'namespace' => 'App\Controllers\Admin']);

/**
 * Menu Content
 */
$routes->get('/menu_dua', 'Menu_dua::index', ['filter' => 'auth:N, 2, 1', 'namespace' => 'App\Controllers']);
$routes->get('/menu_satu', 'Menu_satu::index', ['filter' => 'auth:N, 1, 1', 'namespace' => 'App\Controllers']);
