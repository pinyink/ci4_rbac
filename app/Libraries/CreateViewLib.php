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
        $arrayTableJs = [];
        foreach ($this->fields as $key => $value) {
            if ($value['field_database'] == 1) {
                $tableTh .= "\n\t\t\t\t\t\t\t\t\t".'<th style="width: '.$width.'%">'.$value['name_alias'].'</th>';
                array_push($arrayTableJs, "{data: '".$value['name_field']."'}");
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
        <li class=\"breadcrumb-item active\">".ucwords($this->table['title'])."</li>
    </ol>
</div>

<!-- Container -->
<div class=\"page-content fade-in-up\">
    <!-- Row -->
    <div class=\"row\">
        <div class=\"col-xl-12 col-lg-12 col-md-12\">
            @?=session()->getFlashData('message');?@
            <div class=\"ibox\">
                <div class=\"ibox-body\">
                    @?php if(enforce(".$this->table['rbac'].", 2)): ?@
                        <a href='@?=base_url('".$this->table['routename']."/tambah')?@' class='btn btn-info btn-sm'><i class=\"fa fa-plush\"></i> Tambah Data</a>
                    @?php endif ?@
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
                        <a onclick=\"reload_table()\" class=\"refresh\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"reload data\"><i class=\"fa fa-refresh\"></i></a>
                    </div>
                </div>
                <div class=\"ibox-body\">
                    <div class=\"table-responsive\">
                        <table id=\"datatable\" class=\"table table-striped table-bordered table-hover\">
                            <thead>
                                <tr>
                                    <th width=\"15%\">Aksi</th>
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
                    responsive: true,
                    processing: true,
                    serverSide: true,
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
                    columns: [
                        {data: 'action'},
                        {data: 'no'},
                        ".implode(",\n\t\t\t\t\t\t\t", $arrayTableJs)."
                    ],
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
                url: \"@?=base_url('".$this->table['routename']."')?@/\"+id+'/delete',
                data: {'@?=csrf_token()?@' : '@?=csrf_hash()?@'},
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
        if (!file_exists(ROOTPATH.'app/Views/'.$this->table['routename'])) {
            mkdir(ROOTPATH.'app/Views/'.$this->table['routename'], 775);
        }
        $pathView = ROOTPATH.'app/Views/'.$this->table['routename'].'/index.php';
        $view = str_replace('@?', '<?', $view);
        $view = str_replace('?@', '?>', $view);
        $create = fopen($pathView, "w") or die("Change your permision folder for application and harviacode folder to 777");
        fwrite($create, $view);
        fclose($create);

        $this->tambahView('Tambah');
        $this->tambahView('Edit');
        $this->tambahView('Detail');
        $this->tambahForm();
        $this->viewDetail();
        return true;
    }

    public function tambahView($aksi)
    {
        $include = "@?= \$this->include('".$this->table['table']."/_form_".$this->table['table']."'); ?@";
        if ($aksi == 'Detail') {
            $include = "@?= \$this->include('".$this->table['table']."/_detail'); ?@";
        }
        $view = "
@?= \$this->extend('tema/tema'); ?@ 
@?=\$this->section('css');?@
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
        <li class=\"breadcrumb-item\">".$aksi."</li>
    </ol>
</div>

<!-- Container -->
<div class=\"page-content fade-in-up\">
    <!-- Row -->
    <div class=\"row\">
        <div class=\"col-xl-12 col-lg-12 col-md-12\">
            @?=session()->getFlashData('message');?@
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
                    <div class=\"ibox-title\">".$aksi." ".$this->table['title']."</div>
                    <div class=\"ibox-tools\">
                    </div>
                </div>
                <div class=\"ibox-body\">
                    <div class=\"row\">
                        <div class=\"col-md-12\">
                            ".$include."
                        </div>
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

@?=\$this->endSection();?@
";
        if (!file_exists(ROOTPATH.'app/Views/'.$this->table['routename'])) {
            mkdir(ROOTPATH.'app/Views/'.$this->table['routename'].'/'.strtolower($aksi).'.php', 775);
        }
        $pathView = ROOTPATH.'app/Views/'.$this->table['routename'].'/'.strtolower($aksi).'.php';
        $view = str_replace('@?', '<?', $view);
        $view = str_replace('?@', '?>', $view);
        $create = fopen($pathView, "w") or die("Change your permision folder for application and harviacode folder to 777");
        fwrite($create, $view);
        fclose($create);

        return true;
    }

    public function tambahForm()
    {
        $form = "@?= form_open_multipart(\$url, [], ['id' => \$id, 'method' => \$method]); ?@";
        foreach ($this->fields as $key => $value) {
            if ($value['name_type'] == 'text') {
                $form .= "\n\t<div class=\"form-group\">
                    \n\t\t@?= form_label('".$value['name_alias']."'); ?@
                    \n\t\t@?php \$invalid = session('_ci_validation_errors.".$value['name_field']."') ? 'is-invalid' : ''; ?@
                    \n\t\t@?php \$value = isset(\$".$this->table['table']."['".$value['name_field']."']) ? \$".$this->table['table']."['".$value['name_field']."'] : old('".$value['name_field']."'); ?@
                    \n\t\t<div class=\"input-group\">
                        \n\t\t\t@?= form_input('".$value['name_field']."', trim(\$value), ['class' => 'form-control '.\$invalid, 'placeholder' => '".$value['name_alias']."']); ?@
                    \n\t\t</div>
                    \n\t\t@?php if(session('_ci_validation_errors.".$value['name_field']."')):?@
                        \n\t\t\t<div class=\"text-danger\">@?=session('_ci_validation_errors.".$value['name_field']."')?@</div>
                    \n\t\t@?php endif ?@
                \n\t</div>";
            }

            if ($value['name_type'] == 'textarea') {
                $form .= "\n\t<div class=\"form-group\">
                    \n\t\t@?= form_label('".$value['name_alias']."'); ?@
                    \n\t\t@?php \$invalid = session('_ci_validation_errors.".$value['name_field']."') ? 'is-invalid' : ''; ?@
                    \n\t\t@?php \$value = isset(\$".$this->table['table']."['".$value['name_field']."']) ? \$".$this->table['table']."['".$value['name_field']."'] : old('".$value['name_field']."'); ?@
                    \n\t\t<div class=\"input-group\">
                        \n\t\t\t@?= form_textarea('".$value['name_field']."', trim(\$value), ['class' => 'form-control '.\$invalid, 'rows' => '3', 'placeholder' => '".$value['name_alias']."']); ?@
                    \n\t\t</div>
                    \n\t\t@?php if(session('_ci_validation_errors.".$value['name_field']."')):?@
                        \n\t\t\t<div class=\"text-danger\">@?=session('_ci_validation_errors.".$value['name_field']."')?@</div>
                    \n\t\t@?php endif ?@
                \n\t</div>";
            }

            if ($value['name_type'] == 'number') {
                $form .= "\n\t<div class=\"form-group\">
                    \n\t\t@?= form_label('".$value['name_alias']."'); ?@
                    \n\t\t@?php \$invalid = session('_ci_validation_errors.".$value['name_field']."') ? 'is-invalid' : ''; ?@
                    \n\t\t@?php \$value = isset(\$".$this->table['table']."['".$value['name_field']."']) ? \$".$this->table['table']."['".$value['name_field']."'] : old('".$value['name_field']."'); ?@
                    \n\t\t@?php \$value = \$value != null ? number_format(\$value, 0, ',', '.') : 0; ?@
                    \n\t\t<div class=\"input-group\">
                        \n\t\t\t@?= form_input('".$value['name_field']."', trim(\$value), ['class' => 'form-control '.\$invalid, 'placeholder' => '".$value['name_alias']."']); ?@
                    \n\t\t</div>
                    \n\t\t@?php if(session('_ci_validation_errors.".$value['name_field']."')):?@
                        \n\t\t\t<div class=\"text-danger\">@?=session('_ci_validation_errors.".$value['name_field']."')?@</div>
                    \n\t\t@?php endif ?@
                \n\t</div>";
            }

            if ($value['name_type'] == 'rupiah') {
                $form .= "\n\t<div class=\"form-group\">
                    \n\t\t@?= form_label('".$value['name_alias']."'); ?@
                    \n\t\t@?php \$invalid = session('_ci_validation_errors.".$value['name_field']."') ? 'is-invalid' : ''; ?@
                    \n\t\t@?php \$value = isset(\$".$this->table['table']."['".$value['name_field']."']) ? \$".$this->table['table']."['".$value['name_field']."'] : old('".$value['name_field']."'); ?@
                    \n\t\t@?php \$value = \$value != null ? number_format(\$value, 0, ',', '.') : 0; ?@
                    \n\t\t<div class=\"input-group\">
			            \n\t\t\t<div class=\"input-group-addon bg-white\">Rp</div>
                        \n\t\t\t@?= form_input('".$value['name_field']."', trim(\$value), ['class' => 'form-control '.\$invalid, 'placeholder' => '".$value['name_alias']."']); ?@
                    \n\t\t</div>
                    \n\t\t@?php if(session('_ci_validation_errors.".$value['name_field']."')):?@
                        \n\t\t\t<div class=\"text-danger\">@?=session('_ci_validation_errors.".$value['name_field']."')?@</div>
                    \n\t\t@?php endif ?@
                \n\t</div>";
            }

            if ($value['name_type'] == 'date') {
                $form .= "\n\t<div class=\"form-group\">
                    \n\t\t@?= form_label('".$value['name_alias']."'); ?@
                    \n\t\t@?php \$invalid = session('_ci_validation_errors.".$value['name_field']."') ? 'is-invalid' : ''; ?@
                    \n\t\t@?php \$value = isset(\$".$this->table['table']."['".$value['name_field']."']) ? date('d-m-Y', strtotime(\$".$this->table['table']."['".$value['name_field']."'])) : old('".$value['name_field']."'); ?@
                    \n\t\t<div class=\"input-group\">
			            \n\t\t\t<div class=\"input-group-addon bg-white\"><i class=\"fa fa-calendar\"></i></div>
                        \n\t\t\t@?= form_input('".$value['name_field']."', trim(\$value), ['class' => 'form-control '.\$invalid, 'placeholder' => '".$value['name_alias']."', 'autocomplete' => 'off']); ?@
                    \n\t\t</div>
                    \n\t\t@?php if(session('_ci_validation_errors.".$value['name_field']."')):?@
                        \n\t\t\t<div class=\"text-danger\">@?=session('_ci_validation_errors.".$value['name_field']."')?@</div>
                    \n\t\t@?php endif ?@
                \n\t</div>";
            }

            if ($value['name_type'] == 'image') {
                $form .= "\n\t<div class=\"form-group\">
                    \n\t\t@?php \$invalid = session('_ci_validation_errors.".$value['name_field']."') ? 'is-invalid' : ''; ?@
                    \n\t\t@?php \$value = isset(\$".$this->table['table']."['".$value['name_field']."']) ? \$".$this->table['table']."['".$value['name_field']."'] : 'assets/admincast/dist/assets/img/image.jpg'; ?@
                    \n\t\t<img src=\"@?=base_url(\$value);?@\" style=\"width: 230px; height: 230px\" class=\"img img-thumbnail\" id='img-gambar-".$value['name_field']."'><br>

                    \n\t\t@?= form_label('".$value['name_alias']."', '', ['class' => 'mt-2']); ?@
                    \n\t\t\t@?= form_upload('".$value['name_field']."', trim(\$value), ['class' => 'form-control '.\$invalid, 'accept' => \".png,.jpg,.jpeg\", 'onchange' => \"readURL(this, 'img-gambar-".$value['name_field']."');\"]); ?@
                    \n\t\t@?php if(session('_ci_validation_errors.".$value['name_field']."')):?@
                        \n\t\t\t<div class=\"text-danger\">@?=session('_ci_validation_errors.".$value['name_field']."')?@</div>
                    \n\t\t@?php endif ?@
                \n\t</div>";
            }

            if ($value['name_type'] == 'pdf') {
                $form .= "\n\t<div class=\"form-group\">
                    \n\t\t@?php \$invalid = session('_ci_validation_errors.".$value['name_field']."') ? 'is-invalid' : ''; ?@
                    \n\t\t@?php \$value = isset(\$".$this->table['table']."['".$value['name_field']."']) ? '<a href=\"'.base_url(\$".$this->table['table']."['".$value['name_field']."']).'\" target=\"_blank\" class=\"text-primary\">( Download )</a>' : ''; ?@

                    \n\t\t@?= form_label('".$value['name_alias']."'.' '.\$value, '', ['class' => 'mt-2']); ?@
                    \n\t\t\t@?= form_upload('".$value['name_field']."', '', ['class' => 'form-control '.\$invalid, 'accept' => \".pdf\"]); ?@
                    \n\t\t@?php if(session('_ci_validation_errors.".$value['name_field']."')):?@
                        \n\t\t\t<div class=\"text-danger\">@?=session('_ci_validation_errors.".$value['name_field']."')?@</div>
                    \n\t\t@?php endif ?@
                \n\t</div>";
            }
        }
        $form .= "\n<button class=\"btn btn-primary\" type=\"submit\"><i class=\"fa fa-save\"></i> @?=\$button;?@</button>";
        $form .= "\n@?= form_close(); ?@";

        // def js
        $js = "";
        $css = [];
        $script = [];
        foreach ($this->fields as $key => $value) {
            if($value['name_type'] == "number" || $value['name_type'] == "rupiah") {
                $js .= "\n\t$('[name=\"".$value['name_field']."\"]').keyup(function (e) { \n\t\tthis.value = formatRupiah(this.value);\n\t});";
            }
            if($value['name_type'] == 'date') {
                $js .= "\n\t$('[name=\"".$value['name_field']."\"]').datepicker({\n\t\ttodayBtn: \"linked\",\n\t\tkeyboardNavigation: false,\n\t\tforceParse: false,\n\t\tcalendarWeeks: true,\n\t\tautoclose: true,\n\t\tformat: \"dd-mm-yyyy\"\n\t});";

                // push css
                array_push($css, "<link href=\"@?=base_url();?@/assets/admincast/dist/assets/vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css\" rel=\"stylesheet\" type=\"text/css\" />");

                // push script
                array_push($script, "<script src=\"@?=base_url(); ?@/assets/admincast/dist/assets/vendors/moment/min/moment.min.js\" type=\"text/javascript\"> </script>");
                array_push($script, "<script src=\"@?=base_url(); ?@/assets/admincast/dist/assets/vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js\" type=\"text/javascript\"> </script>");
            }
        }

        // store css to form
        $form .= "\n\n@?php \$this->section('css'); ?@\n".implode("\n", $css)."\n@?php \$this->endSection(); ?@";

        // store js to form
        $form .= "\n\n@?php \$this->section('js'); ?@\n".implode("\n", $script)."\n<script>".$js."\n</script>\n@?php \$this->endSection(); ?@";

        if (!file_exists(ROOTPATH.'app/Views/'.$this->table['routename'])) {
            mkdir(ROOTPATH.'app/Views/'.$this->table['routename'].'/_form_'.$this->table['routename'].'.php', 775);
        }
        $path = ROOTPATH.'app/Views/'.$this->table['routename'].'/_form_'.$this->table['routename'].'.php';
        $form = str_replace('@?', '<?', $form);
        $form = str_replace('?@', '?>', $form);
        $create = fopen($path, "w") or die("Change your permision folder for application and harviacode folder to 777");
        fwrite($create, $form);
        fclose($create);

        return true;
    }

    public function viewDetail()
    {
        $tr = "";
        foreach ($this->fields as $key => $value) {
            if ($value['name_type'] == 'rupiah') {
                $tr .= "\n\t<tr>\n\t\t<td style=\"width: 25%;\">".$value['name_alias']."</td>\n\t\t<td style=\"width: 1%;\">:</td>\n\t\t<th style=\"width: 75%;\">@?='Rp '.number_format(\$".$this->table['table']."['".$value['name_field']."'], 0, ',', '.'); ?@</th>\n\t</tr>";
            } else if($value['name_type'] == 'number') {
                $tr .= "\n\t<tr>\n\t\t<td style=\"width: 25%;\">".$value['name_alias']."</td>\n\t\t<td style=\"width: 1%;\">:</td>\n\t\t<th style=\"width: 75%;\">@?=number_format(\$".$this->table['table']."['".$value['name_field']."'], 0, ',', '.'); ?@</th>\n\t</tr>";
            } else if($value['name_type'] == 'date') {
                $tr .= "\n\t<tr>\n\t\t<td style=\"width: 25%;\">".$value['name_alias']."</td>\n\t\t<td style=\"width: 1%;\">:</td>\n\t\t<th style=\"width: 75%;\">@?=date('d-m-Y', strtotime(\$".$this->table['table']."['".$value['name_field']."'])); ?@</th>\n\t</tr>";
            } else if($value['name_type'] == 'image') {
                $tr .= "\n\t<tr>\n\t\t<td style=\"width: 25%;\">".$value['name_alias']."</td>\n\t\t<td style=\"width: 1%;\">:</td>\n\t\t<th style=\"width: 75%;\"><img src=\"@?=base_url(\$".$this->table['table']."['".$value['name_field']."']); ?@\" class=\"img img-thumbnail\" style=\"width: 240px; height: 240px;\"></th>\n\t</tr>";
            } else {
                $tr .= "\n\t<tr>\n\t\t<td style=\"width: 25%;\">".$value['name_alias']."</td>\n\t\t<td style=\"width: 1%;\">:</td>\n\t\t<th style=\"width: 75%;\">@?=esc(\$".$this->table['table']."['".$value['name_field']."']); ?@</th>\n\t</tr>";
            }
        }
        $table = "<table class=\"table\">".$tr."\n</table>";
        if (!file_exists(ROOTPATH.'app/Views/'.$this->table['routename'])) {
            mkdir(ROOTPATH.'app/Views/'.$this->table['routename'].'/_detail.php', 775);
        }
        $path = ROOTPATH.'app/Views/'.$this->table['routename'].'/_detail.php';
        $table = str_replace('@?', '<?', $table);
        $table = str_replace('?@', '?>', $table);
        $create = fopen($path, "w") or die("Change your permision folder for application and harviacode folder to 777");
        fwrite($create, $table);
        fclose($create);

        return true;
    }
}