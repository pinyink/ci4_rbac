<?php $this->extend('tema/tema'); ?>
<?php $this->section('css'); ?>
<style>
    #v-pills-tab, .tab-content {
        border: 1px solid rgba(10, 10, 10, 0.3);
        border-radius: 5px;
        padding: 5px;
        min-height: 50vh;
    }
</style>
<?php $this->endSection(); ?>
<?php $this->section('content'); ?>
<div class="page-heading">
    <h1 class="page-title">Admin</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?=base_url('home');?>"><i class="fa fa-home font-20"></i></a>
        </li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item">Setting</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="ibox">
        <div class="ibox-head">
            Settings
        </div>
        <div class="ibox-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Home</a>
                        <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Profile</a>
                        <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">Messages</a>
                        <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">Settings</a>
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">Home</div>
                        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">Profile</div>
                        <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">Messages</div>
                        <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">Settings</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>