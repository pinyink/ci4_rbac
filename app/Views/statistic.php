
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
                                    <input type="text" class="form-control" value="<?=date('m-Y')?>" name="tanggal">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-filter"></i> Filter</button>
                                    </div>
                                </div>
                            </div>
                        <?=form_close();?>
                    </div>
                </div>
                <div class="ibox-body">
                    <div id="morris_line_chart" style="height:280px;"></div>
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
        Morris.Line({
        element: 'morris_line_chart',
            data: [
                { year: '2010', value: 5 },
                { year: '2011', value: 11 },
                { year: '2012', value: 5 },
                { year: '2013', value: 7 },
                { year: '2014', value: 10 },
                { year: '2015', value: 8 },
                { year: '2016', value: 15 },
                { year: '2017', value: 26 },
            ],
        xkey: 'year',
        ykeys: ['value'],
        resize: true,
        lineWidth:4,
        labels: ['Value'],
        lineColors: ['#3498db'],
        pointSize:5,
    });
    }

    $('input[name="tanggal"]').datepicker({
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
