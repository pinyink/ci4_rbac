<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Permission extends Controller
{
    public function index()
    {
        $enforcer = \Config\Services::enforcer();
        $role = $enforcer->getAllRoles();
        print_r($role);
        $policy = $enforcer->getPolicy();
        print_r($policy);
    }

    public function addPermissionUserForRole()
    {
        $request = \Config\Services::request();
        $enforcer = \Config\Services::enforcer();
        $role = $request->getGet('role');
        $user = $request->getGet('user');
        $enforcer->addRoleForUser($user, $role);
    }

    public function viewPermissionUserForRole()
    {
        $request = \Config\Services::request();
        $role = $request->getGet('role');
        $enforcer = \Config\Services::enforcer();
        $user = $enforcer->getUsersForRole($role);
        print_r($user);
    }

    public function addPermissionAddPolicy()
    {
        $request = \Config\Services::request();
        $role = $request->getGet('role');
        $menu = $request->getGet('menu');
        $permission = $request->getGet('permission');
        $enforcer = \Config\Services::enforcer();
        $query = $enforcer->addPolicy($role, $menu, $permission);
        print_r($query);
    }

    public function cekPermissionUser()
    {
        $enforcer = \Config\Services::enforcer();
        if ($enforcer->enforce("pindi", "content", "tambah")) {
            // permit eve to edit articles
            echo "anda punya akses";
        } else {
            // deny the request, show an error
            echo "anda tidak mempunyai akses";
        }

        $cache = \Config\Services::cache();

        $foo = $cache->get('rules');
        print_r($foo);
    }

    public function coba()
    {
        $cache = \Config\Services::cache();

        $foo = $cache->get('rules');
        print_r($foo);
        // return view('coba');
    }
}
