<?php

function hasRoleForUser($user, $role)
{
    $enforcer = \Config\Services::enforcer();
    return $enforcer->hasRoleForUser($user, $role);
}

function addRoleForUser($user, $role)
{
    $enforcer = \Config\Services::enforcer();
    return $enforcer->addRoleForUser($user, $role);
}

function deleteRoleForUser($user, $role)
{
    $enforcer = \Config\Services::enforcer();
    return $enforcer->deleteRoleForUser($user, $role);
}

function addPolicy($role, $menu, $menu_akses)
{
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
    $enforcer = \Config\Services::enforcer();
    return $enforcer->removePolicy($role, $menu, $menu_akses);
}

function enforce($menu, $menu_akses)
{
    if (session('level') == 1) {
        return true;
    } else {
        $enforcer = \Config\Services::enforcer();
        return $enforcer->enforce(session('user_id'), $menu, $menu_akses);
    }
}
