<?= $this->extend('tema/tema'); ?>
<?= $this->section('css'); ?>
<link rel="stylesheet" href="<?= base_url(); ?>/assets/alertifyjs/css/alertify.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>/assets/alertifyjs/css/themes/bootstrap.min.css">
<?= $this->endSection(); ?>
<?= $this->section('content'); ?>
<div class="page-heading">
    <h1 class="page-title">Profile</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="index.html"><i class="la la-home font-20"></i></a>
        </li>
        <li class="breadcrumb-item">Profile</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-lg-3 col-md-4">
            <div class="ibox">
                <div class="ibox-body text-center">
                    <div class="m-t-20">
                        <img class="img-circle imgProfil" src="" />
                    </div>
                    <h5 class="font-strong m-b-10 m-t-10" id="textName"></h5>
                    <div class="m-b-20 text-muted" id="textEmail"></div>
                    <div class="profile-social m-b-20">
                        <a href="javascript:;"><i class="fa fa-twitter"></i></a>
                        <a href="javascript:;"><i class="fa fa-facebook"></i></a>
                        <a href="javascript:;"><i class="fa fa-pinterest"></i></a>
                        <a href="javascript:;"><i class="fa fa-dribbble"></i></a>
                    </div>
                    <p class="text-center" id="textBio"></p>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-8">
            <div class="ibox">
                <div class="ibox-body">
                    <ul class="nav nav-tabs tabs-line">
                        <li class="nav-item">
                            <a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="ti-bar-chart"></i> Overview</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tab-2" data-toggle="tab"><i class="ti-settings"></i> Settings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tab-3" data-toggle="tab"><i class="ti-image"></i> Foto</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tab-4" data-toggle="tab"><i class="ti-key"></i> Password</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab-1">
                            <div class="row">
                                <div class="col-md-12">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-2">
                            <form action="javascript:void(0)" id="form-setting">
                                <input type="hidden" name="idSetting" value="<?= session('user_id'); ?>">
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label>First Name</label>
                                        <input class="form-control" type="text" placeholder="First Name" name="firstName" required>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label>Last Name</label>
                                        <input class="form-control" type="text" placeholder="Last Name" name="lastName" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input class="form-control" type="text" placeholder="Email address" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label>Small Bio</label>
                                    <textarea class="form-control" name="bio"></textarea>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-info" type="submit">Save</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="tab-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <form class="form-horizontal" id="formFoto" enctype="multipart/form-data">
                                        <div class="form-group row">
                                            <label for="inputFile" class="col-md-2 col-xs-4 col-form-label">Pilih File</label>
                                            <div class="col-md-2 col-xs-6">
                                                <input type="file" class="form-control" name="inputFile" id="inputFile" placeholder="">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-md-2 col-xs-4 col-form-label"></label>
                                            <div class="col-md-4 col-xs-6">
                                                <div id="imgView"></div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="offset-sm-2 col-sm-10">
                                                <button type="submit" class="btn btn-danger">simpan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-4">
                            <form action="javascript:void(0)" id="form-password">
                                <div class="form-group">
                                    <label>Password Lama</label>
                                    <input class="form-control" type="password" placeholder="Old Password" name="oldPassword" required>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label>Password Baru</label>
                                        <input class="form-control" type="password" placeholder="New Password" name="newPassword" required>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label>Ulangi Password</label>
                                        <input class="form-control" type="password" placeholder="Retype Password" name="retypePassword" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-info" type="submit">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
<?= $this->section('js'); ?>
<script src="<?= base_url(); ?>/assets/alertifyjs/alertify.min.js"></script>
<script src="<?= base_url(); ?>/assets/admincast/dist/assets/vendors/jquery-validation/dist/jquery.validate.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function() {
        $('#form-setting').validate({
            errorClass: "help-block",
            rules: {
                firstName: {
                    required: true
                },
                lastName: {
                    required: true
                },
                email: {
                    required: true
                }
            },
            highlight: function(e) {
                $(e).closest(".form-group").addClass("has-error")
            },
            unhighlight: function(e) {
                $(e).closest(".form-group").removeClass("has-error")
            },
            submitHandler: function() {
                var dataString = $('#form-setting').serialize() + '&token=' + $('meta[name=TOKEN]').attr("content");
                $.ajax({
                        'url': '<?= base_url(); ?>/setting/profil/update',
                        'type': 'post',
                        'headers': {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        'dataType': 'JSON',
                        'data': dataString,
                    })
                    .done(function(data) {
                        get_data();
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.notify('<span><i class="fa fa-bell"></i> ' + data.errorMessage + '</span>', data.errorType, 5, function() {
                            console.log('dismissed');
                        });
                    })
                    .fail(function(jqXHR) {
                        console.log(jqXHR.responseText);
                    })
                    .always(function() {
                        console.log('reconnect success');
                    });
            }
        });

        $('#form-password').validate({
            errorClass: "help-block",
            rules: {
                oldPassword: {
                    required: true
                },
                newPassword: {
                    required: true
                },
                retypePassword: {
                    required: true
                }
            },
            highlight: function(e) {
                $(e).closest(".form-group").addClass("has-error")
            },
            unhighlight: function(e) {
                $(e).closest(".form-group").removeClass("has-error")
            },
            submitHandler: function() {
                var dataString = $('#form-password').serialize() + '&token=' + $('meta[name=TOKEN]').attr("content");
                $.ajax({
                        'url': '<?= base_url(); ?>/setting/profil/updatePassword',
                        'type': 'post',
                        'headers': {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        'dataType': 'JSON',
                        'data': dataString,
                    })
                    .done(function(data) {
                        get_data();
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.notify('<span><i class="fa fa-bell"></i> ' + data.errorMessage + '</span>', data.errorType, 5, function() {
                            console.log('dismissed');
                        });
                    })
                    .fail(function(jqXHR) {
                        console.log(jqXHR.responseText);
                    })
                    .always(function() {
                        console.log('reconnect success');
                    });
            }
        });
    });

    $('#formFoto').on('submit', function(event) {
        event.preventDefault();
        var dataString = new FormData($('#formFoto')[0]);
        dataString.append('csrf_test_name', $('meta[name=csrf_cookie_name]').attr("content"));
        $.ajax({
                'url': '<?= base_url(); ?>/setting/profil/updateFoto',
                'type': 'post',
                'headers': {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                'dataType': 'JSON',
                'data': dataString,
                'processData': false,
                'contentType': false,
                'cache': false,
            })
            .done(function(data) {
                get_data();
                alertify.set('notifier', 'position', 'top-right');
                alertify.notify('<span><i class="fa fa-bell"></i> ' + data.errorMessage + '</span>', data.errorType, 5, function() {
                    console.log('dismissed');
                });
            })
            .fail(function(jqXHR) {
                console.log(jqXHR.responseText);
            })
            .always(function() {
                console.log('reconnect success');
            });
    });

    $(document).ready(function() {
        get_data();
        $("#inputFile").change(function() {
            readURL(this, "imgView");
        });
    });

    function get_data() {
        $.ajax({
                'url': '<?= base_url(); ?>/setting/profil/getData',
                'type': 'get',
                'dataType': 'json',
            })
            .done(function(data) {
                $('#textName').text(data.data.profil_firstname + ' ' + data.data.profil_lastname);
                $('#textEmail').text(data.data.profil_email);
                $('#textBio').text(data.data.profil_bio);
                $('[name="firstName"]').val(data.data.profil_firstname);
                $('[name="lastName"]').val(data.data.profil_lastname);
                $('[name="email"]').val(data.data.profil_email);
                $('[name="bio"]').val(data.data.profil_bio);
                $('.imgProfil').attr('src', data.data.profil_image);
            });
    }

    function readURL(input, id) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#' + id).html('<img width="100%" class="img img-rounded" src="' + e.target.result + '" height="300" id="previewHolder" alt="">');
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            alert('silahkan pilih gambar');
        }
    }
</script>
<?= $this->endSection(); ?>