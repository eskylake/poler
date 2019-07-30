<?php
namespace App\views\layouts;
use App\core\Path;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Page Title</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/open-iconic/1.1.1/font/css/open-iconic-bootstrap.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container mt-5">
        <div class="col-12">
            <?php 
                if (isset($data['alert'])):
                    if ($data['alert']['type'] == 'error'):
            ?>
                        <div class="alert alert-danger" role="alert">
                            <h5><?= $data['alert']['text'] ?></h5>
                        </div>
            <?php
                    elseif ($data['alert']['type'] == 'success'):
            ?>
                        <div class="alert alert-success" role="alert">
                            <h5><?= $data['alert']['text'] ?></h5>
                        </div>
            <?php
                    endif;
                endif;
            ?>
        </div>
    </div>
    <div class="container">
        <div class="row pt-5">
            <div class="col-12 col-md-6 offset-md-3">
                <?php require_once Path::VIEW_PATH . $path . '.php'; ?>
            </div>
        </div>
    </div>
    
</body>
</html>
