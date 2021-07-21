<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $enforcer = \Config\Services::enforcer();
    if ($enforcer->enforce("pindi", "content", "tambah")) {
        // permit eve to edit articles
        echo "anda punya akses";
    } else {
        // deny the request, show an error
        echo "anda tidak mempunyai akses";
    }
    ?>
</body>

</html>