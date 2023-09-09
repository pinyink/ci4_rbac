<?php

function hasRoleForUser($user, $role)
{
    $enforcer = \Config\Services::enforcer();
    return $enforcer->hasRoleForUser($user, $role);
}

function addRoleForUser($user, $role)
{
    $cache = \Config\Services::cache();
    $cache->delete('rules');
    $enforcer = \Config\Services::enforcer();
    return $enforcer->addRoleForUser($user, $role);
}

function deleteRoleForUser($user, $role)
{
    $cache = \Config\Services::cache();
    $cache->delete('rules');
    $enforcer = \Config\Services::enforcer();
    return $enforcer->deleteRoleForUser($user, $role);
}

function addPolicy($role, $menu, $menu_akses)
{
    $cache = \Config\Services::cache();
    $cache->delete('rules');
    $enforcer = \Config\Services::enforcer();
    return $enforcer->addPolicy($role, $menu, $menu_akses);
}

function hasPolicy($role, $menu, $menu_akses)
{
    $enforcer = \Config\Services::enforcer();
    return $enforcer->hasPolicy($role, $menu, $menu_akses);
}

function removePolicy($role, $menu, $menu_akses)
{
    $cache = \Config\Services::cache();
    $cache->delete('rules');
    $enforcer = \Config\Services::enforcer();
    return $enforcer->removePolicy($role, $menu, $menu_akses);
}

function enforce($menu, $menu_akses)
{
    if (session('level') == 1) {
        return true;
    } else {
        $enforcer = \Config\Services::enforcer();
        $q = $enforcer->getPolicy();
        foreach ($q as $key => $value) {
            if (session('user_level') == $value[0] && $menu == $value[1] && $menu_akses == $value[2]) {
                return true;
            }
        } 
        return false;
    }
}
