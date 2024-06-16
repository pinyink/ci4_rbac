<?php

namespace App\Libraries;

class CreateViewLib
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

    public function index()
    {
        $cssScript = "";
        $jsScript = "";
        $jqFungsi = "";
        $jqReady = "";
        $jumlah = 0;
        foreach ($this->fields as $key => $value){
            if ($value['field_database'] == 1) {
                $jumlah++;
            }
        }
        $countfieldTable = count($this->fields);
        $width = 75/$jumlah;
        $tableTh = '';
        foreach ($this->fields as $key => $value) {
            if ($value['field_database'] == 1) {
                $tableTh .= "\n\t\t\t\t\t\t\t\t\t".'<th style="width: '.$width.'%">'.$value['name_alias'].'</th>';
            }
        }
$view = "
@?= \$this->extend('tema/tema'); ?@ 
@?=\$this->section('css');?@
<!-- Data Table CSS -->
<link href=\"@?=base_url();?@/assets/alertifyjs/css/alertify.min.css\" rel=\"stylesheet\" type=\"text/css\" />
<link href=\"@?=base_url();?@/assets/alertifyjs/css/themes/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\" />
<link href=\"@?=base_url();?@/assets/admincast/dist/assets/vendors/DataTables/datatables.min.css\" rel=\"stylesheet\" type=\"text/css\" />
<link href=\"@?=base_url();?@/assets/admincast/dist/assets/vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css\" rel=\"stylesheet\" type=\"text/css\" />
".$cssScript."
@?=\$this->endSection();?@

@?=\$this->section('content'); ?@
<div class=\"page-heading\">
    <h1 class=\"page-title\">".ucwords($this->table['title'])."</h1>
    <ol class=\"breadcrumb\">
        <li class=\"breadcrumb-item\">
            <a href=\"@?=base_url('home');?@\"><i class=\"fa fa-home font-20\"></i></a>
        </li>
        <li class=\"breadcrumb-item\">".ucwords($this->table['title'])."</li>
        <li class=\"breadcrumb-item\">Lihat</li>
    </ol>
</div>

<!-- Container -->
<div class=\"page-content fade-in-up\">
    <!-- Row -->
    <div class=\"row\">
        <div class=\"col-xl-12 col-lg-12 col-md-12\">
            <div class=\"ibox\">
                <div class=\"ibox-head\">
                    <div class=\"ibox-title\">Data ".$this->table['title']."</div>
                    <div class=\"ibox-tools\">
                        <a onclick=\"reload_table()\" class=\"refresh\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"reload data\"><i class=\"fa fa-refresh\"></i></a>
                        @?php if(enforce(".$this->table['rbac'].", 2)): ?@
                            <a class=\"\" onclick=\"tambah_data()\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"tambah data\"><i class=\"fa fa-plus-square\"></i></a>
                        @?php endif ?@
                    </div>
                </div>
                <div class=\"ibox-body\">
                    <div class=\"table-responsive\">
                        <table id=\"datatable\" class=\"table table-striped table-bordered table-hover\">
                            <thead>
                                <tr>
                                    <th width=\"15%\">Action</th>
                                    <th width=\"10%\">No</th>".$tableTh."
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Row -->
</div>
<!-- /Container -->
@?=\$this->endSection();?@
@?=\$this->section('js');?@
<script src=\"@?=base_url(); ?@/assets/alertifyjs/alertify.min.js\" type=\"text/javascript\"> </script>
<script src=\"@?=base_url(); ?@/assets/admincast/dist/assets/vendors/jquery-validation/dist/jquery.validate.min.js\" type=\"text/javascript\"> </script>
<script src=\"@?=base_url(); ?@/assets/admincast/dist/assets/vendors/DataTables/datatables.min.js\" type=\"text/javascript\"> </script>
".$jsScript."
<script>
    var table;
    var save_method;
    \$(document).ready(function () {
        table = \$('#datatable').DataTable({
                    scrollCollapse: true,
                    responsive: true,
                    autoWidth: false,
                    language: { search: \"\",
                        searchPlaceholder: \"Search\",
                        sLengthMenu: \"_MENU_items\"
                    },
                    \"order\": [],
                    \"ajax\": {
                        \"url\": \"@?php echo base_url('".$this->table['routename']."/ajax_list') ?@\",
                        \"headers\": {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        \"type\": \"POST\",
                        \"data\": {@?=csrf_token();?@: '@?=csrf_hash()?@'},
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR.responseText);
                        }
                    },
                    //optional
                    \"columnDefs\": [{
                        \"targets\": [0, 1],
                        \"orderable\": false,
                    }, ],
                });
    });
    
    function reload_table() {
        table.ajax.reload(null, false);
    }

    ".$jqReady."
    ".$jqFungsi."

    @?php if(enforce(".$this->table['rbac'].", 4)): ?@
    function delete_data(id) {
        Swal.fire({
        title: 'Apa Anda Yakin?',
        text: \"Anda Tidak Dapat Mengembalikan Data Yang Telah Di Hapus\",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya Hapus !'
        }).then((result) => {
        if (result.isConfirmed) {
            \$.ajax({
                type: \"DELETE\",
                url: \"@?=base_url('".$this->table['routename']."')?@/\"+id+'/delete_data',
                dataType: \"json\",
                success: function (response) {
                    if(response.errorCode == 1) {
                        Swal.fire(
                            'Deleted!',
                            'Data Berhasil Di Hapus.',
                            'success'
                        )
                    } else {
                        Swal.fire(
                            'Deleted Failed!',
                            'Data Gagal Di Hapus',
                            'warning'
                        )
                    }
                    reload_table();
                }
            });
        }
        })

    }
    @?php endif ?@
</script>
@?=\$this->endSection();?@
";
        if (!file_exists(ROOTPATH.'app/Views/'.$this->table['table'])) {
            mkdir(ROOTPATH.'app/Views/'.$this->table['table'].'/index.php', 775);
        }
        $pathView = ROOTPATH.'app/Views/'.$this->table['table'].'/index.php';
        $view = str_replace('@?', '<?', $view);
        $view = str_replace('?@', '?>', $view);
        $create = fopen($pathView, "w") or die("Change your permision folder for application and harviacode folder to 777");
        fwrite($create, $view);
        fclose($create);

        $this->tambahView();
        $this->tambahForm();
        return true;
    }

    public function tambahView()
    {
        $view = "
@?= \$this->extend('tema/tema'); ?@ 
@?=\$this->section('css');?@
<!-- Data Table CSS -->
<link href=\"@?=base_url();?@/assets/alertifyjs/css/alertify.min.css\" rel=\"stylesheet\" type=\"text/css\" />
<link href=\"@?=base_url();?@/assets/alertifyjs/css/themes/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\" />
<link href=\"@?=base_url();?@/assets/admincast/dist/assets/vendors/DataTables/datatables.min.css\" rel=\"stylesheet\" type=\"text/css\" />
<link href=\"@?=base_url();?@/assets/admincast/dist/assets/vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css\" rel=\"stylesheet\" type=\"text/css\" />
@?=\$this->endSection();?@

@?=\$this->section('content'); ?@
<div class=\"page-heading\">
    <h1 class=\"page-title\">".ucwords($this->table['title'])."</h1>
    <ol class=\"breadcrumb\">
        <li class=\"breadcrumb-item\">
            <a href=\"@?=base_url('home');?@\"><i class=\"fa fa-home font-20\"></i></a>
        </li>
        <li class=\"breadcrumb-item\">
            <a href=\"@?=base_url('".$this->table['routename']."/index');?@\">".ucwords($this->table['title'])."</a>
        </li>
        <li class=\"breadcrumb-item\">Tambah</li>
    </ol>
</div>

<!-- Container -->
<div class=\"page-content fade-in-up\">
    <!-- Row -->
    <div class=\"row\">
        <div class=\"col-xl-12 col-lg-12 col-md-12\">
            <div class=\"ibox\">
                <div class=\"ibox-body\">
                    <a href='@?=base_url('".$this->table['routename']."/index')?@' class='btn btn-info btn-sm'><i class=\"fa fa-backward\"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>
    <!-- /Row -->
    <!-- Row -->
    <div class=\"row\">
        <div class=\"col-xl-12 col-lg-12 col-md-12\">
            <div class=\"ibox\">
                <div class=\"ibox-head\">
                    <div class=\"ibox-title\">Data ".$this->table['title']."</div>
                    <div class=\"ibox-tools\">
                    </div>
                </div>
                <div class=\"ibox-body\">
                    @?= \$this->include('".$this->table['table']."/_form_".$this->table['table']."'); ?@
                </div>
            </div>
        </div>
    </div>
    <!-- /Row -->
</div>
<!-- /Container -->
@?=\$this->endSection();?@
@?=\$this->section('js');?@

@?=\$this->endSection();?@
";
        if (!file_exists(ROOTPATH.'app/Views/'.$this->table['table'])) {
            mkdir(ROOTPATH.'app/Views/'.$this->table['table'].'/tambah.php', 775);
        }
        $pathView = ROOTPATH.'app/Views/'.$this->table['table'].'/tambah.php';
        $view = str_replace('@?', '<?', $view);
        $view = str_replace('?@', '?>', $view);
        $create = fopen($pathView, "w") or die("Change your permision folder for application and harviacode folder to 777");
        fwrite($create, $view);
        fclose($create);

        return true;
    }

    public function tambahForm()
    {
        $form = "<?= form_open(\$url, [], ['id' => \$id, 'method' => \$method]); ?>";
        foreach ($this->fields as $key => $value) {
            if ($value['name_type'] == 'text') {
                $form .= "\n\t<div class=\"form-group\">\n\t\t<?= form_label('".$value['name_alias']."'); ?>\n\t\t<?= form_input('".$value['name_field']."', '', ['class' => 'form-control']); ?>\n\t</div>";
            }
        }
        $form .= "\n<button class=\"btn btn-primary\" type=\"submit\"><i class=\"fa fa-save\"></i> <?=\$button;?></button>";
        $form .= "\n<?= form_close(); ?>";

        if (!file_exists(ROOTPATH.'app/Views/'.$this->table['table'])) {
            mkdir(ROOTPATH.'app/Views/'.$this->table['table'].'/_form_'.$this->table['table'].'.php', 775);
        }
        $path = ROOTPATH.'app/Views/'.$this->table['table'].'/_form_'.$this->table['table'].'.php';
        $form = str_replace('@?', '<?', $form);
        $form = str_replace('?@', '?>', $form);
        $create = fopen($path, "w") or die("Change your permision folder for application and harviacode folder to 777");
        fwrite($create, $form);
        fclose($create);

        return true;
    }
}