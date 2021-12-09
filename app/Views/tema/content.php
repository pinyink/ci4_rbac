<?php $this->extend('tema/tema'); ?>
<?php $this->section('content'); ?>
<!-- START PAGE CONTENT-->
<div class="page-heading">
    <h1 class="page-title">Typography</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="index.html"><i class="fa fa-home font-20"></i></a>
        </li>
        <li class="breadcrumb-item">Typography</li>
        <li class="breadcrumb-item">Typography 2</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-4">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Bold Headings</div>
                </div>
                <div class="ibox-body">
                    <h1 class="no-margin">h1 Heading</h1><br>
                    <h2 class="no-margin">h2 Heading</h2><br>
                    <h3 class="no-margin">h3 Heading</h3><br>
                    <h4 class="no-margin">h4 Heading</h4><br>
                    <h5 class="no-margin">h5 Heading</h5><br>
                    <h6 class="no-margin">h6. Heading</h6><br>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Bold Headings</div>
                </div>
                <div class="ibox-body">
                    <h2 class="font-light">Font light</h2>
                    <h2>Font Regular</h2>
                    <h2 class="font-strong">Font strong</h2>
                    <h2 class="font-bold">Font bold</h2>
                    <h2 class="font-extra-bold">Font Extra bold</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Simple text</div>
                </div>
                <div class="ibox-body">
                    <p class="lead">This is an example of lead body</p>
                    <p>This is an example of standard <abbr title="It is abbreviation">paragraph</abbr> text. This is a
                        <a href="#">link</a>. Font size <code>14px</code>.
                    </p>
                    <p><small>This is an example of small, fine print text.</small></p>
                    <p><strong>strong text, </strong><b>bold text.</b></p>
                    <p><em>This is an example of <mark>emphasized</mark>, italic text.</em></p>
                    <p class="text-left">Left aligned text.</p>
                    <p class="text-center">Center aligned text.</p>
                    <p class="text-right">Right aligned text.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Text Emphasis Colors</div>
                </div>
                <div class="ibox-body">
                    <p class="text-muted">Simple example of muted text.</p>
                    <p class="text-primary">Simple example of primary text.</p>
                    <p class="text-success">Simple example of success text.</p>
                    <p class="text-info">Simple example of info text.</p>
                    <p class="text-warning">Simple example of warning text.</p>
                    <p class="text-danger">Simple example of danger text.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>