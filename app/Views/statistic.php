
<?= $this->extend('tema/tema'); ?> 
<?=$this->section('css');?>
<!-- Data Table CSS -->
<link href="<?=base_url();?>/assets/alertifyjs/css/alertify.min.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url();?>/assets/alertifyjs/css/themes/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url();?>/assets/admincast/dist/assets/vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?=base_url('assets/admincast/dist/assets/vendors/morris.js/morris.css')?>">
<?=$this->endSection();?>

<?=$this->section('content'); ?>
<div class="page-heading">
    <h1 class="page-title">Statistic Visitor</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?=base_url('home');?>"><i class="fa fa-home font-20"></i></a>
        </li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item">Statistic</li>
    </ol>
</div>

<!-- Container -->
<div class="page-content fade-in-up">
    <!-- Row -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Statistic</div>
                    <div class="ibox-tools">
                        <?=form_open('', ['id' => 'formfilter']);?>
                            <?=csrf_field();?>
                            <div class="form-group mb-0">
                                <div class="input-group">
                                    <input type="text" class="form-control" value="<?=date('m-Y')?>" name="month">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-filter"></i> Filter</button>
                                    </div>
                                </div>
                            </div>
                        <?=form_close();?>
                    </div>
                </div>
                <div class="ibox-body">
                    <div id="morris_line_chart" style="height:60vh;"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Row -->
</div>
<!-- /Container -->

<?=$this->endSection();?>
<?=$this->section('js');?>
<script src="<?=base_url(); ?>/assets/alertifyjs/alertify.min.js" type="text/javascript"> </script>
<script src="<?=base_url(); ?>/assets/admincast/dist/assets/vendors/moment/min/moment.min.js" type="text/javascript"> </script>
<script src="<?=base_url(); ?>/assets/admincast/dist/assets/vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"> </script>
<script src="<?=base_url('assets/admincast/dist/assets/vendors/morris.js/morris.min.js')?>" type="text/javascript"></script>
<script src="<?=base_url('assets/admincast/dist/assets/vendors/raphael/raphael.min.js')?>" type="text/javascript"></script>
<script>

    $(document).ready(function () {
        reload_data();
    });

    function reload_data()
    {
        $.ajax({
            type: "POST",
            url: "<?=base_url('statistic/permonth')?>",
            data: $('#formfilter').serialize(),
            dataType: "JSON",
            success: function (response) {
                var data = [];
                $.each(response.data, function (indexInArray, valueOfElement) { 
                    var arr = {
                        'Date' : String(response.tahun+'-'+response.bulan+'-'+valueOfElement.tanggal),
                        'Total' : valueOfElement.total
                    };
                    data.push(arr);
                });
                $('#morris_line_chart').empty();
                Morris.Line({
                    element: 'morris_line_chart',
                    data:data,
                    xkey: 'Date',
                    ykeys: ['Total'],
                    resize: true,
                    lineWidth:4,
                    labels: ['Total'],
                    lineColors: ['#3498db'],
                    pointSize:5,
                    xLabelAngle: 45,
                    xLabelMargin: 1,
                    xLabelFormat: function (d) {
                        return ("0" + d.getDate()).slice(-2) + '-' + ("0" + (d.getMonth() + 1)).slice(-2) + '-' + d.getFullYear();
                    }
                });
            },
            error: function(jqXHR) {
                console.log(jqXHR.responseText);
            }
        });
    }

    $('input[name="month"]').datepicker({
        autoclose: true,
        minViewMode: 1,
        format: 'mm-yyyy'
    });

    $('#formfilter').on('submit', function(e) {
        e.preventDefault();
        reload_data();
    });
</script>
<?=$this->endSection();?>
