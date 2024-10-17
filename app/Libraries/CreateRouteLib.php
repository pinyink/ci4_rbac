<?php

namespace App\Libraries;

class CreateRouteLib 
{
    private $table;
    private $fields;

    /**
     * Get the value of table
     */ 
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Set the value of table
     *
     * @return  self
     */ 
    public function setTable($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Get the value of fields
     */ 
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Set the value of fields
     *
     * @return  self
     */ 
    public function setFields($fields)
    {
        $this->fields = $fields;

        return $this;
    }

    public function generate()
    {
        $routeName = $this->table['routename'];
        $namespace = $this->table['namespace'];
        $rbac = $this->table['rbac'];
        $namaController = str_replace(' ', '', ucwords(str_replace('_', ' ', $this->table['table']))).'Controller';
        $route = "
\$routes->group('".$routeName."', ['namespace' => 'App\Controllers".$namespace."'], static function(\$routes) {
    \$routes->get('index', '".$namaController."::index', ['filter' => 'auth:Y,".$rbac.",1']);
    \$routes->post('ajax_list', '".$namaController."::ajaxList', ['filter' => 'auth:N,".$rbac.",1']);
    \$routes->get('tambah', '".$namaController."::tambahData', ['filter' => 'auth:N,".$rbac.",2']);
    \$routes->post('save', '".$namaController."::saveData', ['filter' => 'auth:N,".$rbac.",2']);
    \$routes->get('(:num)/detail', '".$namaController."::detailData/$1', ['filter' => 'auth:Y,".$rbac.",1']);
    \$routes->get('(:num)/edit', '".$namaController."::editData/$1', ['filter' => 'auth:N,".$rbac.",3']);
    \$routes->post('update', '".$namaController."::saveData', ['filter' => 'auth:N,".$rbac.",3']);
    \$routes->delete('(:num)/delete', '".$namaController."::deleteData/$1', ['filter' => 'auth:N,".$rbac.",4']);
});";
        return $route;
    }
}