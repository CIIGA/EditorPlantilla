<?php
require "cnx/cnx.php";

$sql_modulos=sqlsrv_query($cnx,"SELECT * FROM modulos");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/pdf.css">
    <title>Editor de Plantilla</title>
    <style>
        body {
            background-image: url(img/back.jpg);
            background-repeat: repeat;
            background-size: 100%;
            background-attachment: fixed;
            overflow-x: hidden;
            /* ocultar scrolBar horizontal*/
        }

        body {
            font-family: sans-serif;
            font-style: normal;
            font-weight: normal;
            width: 100%;
            height: 100%;
            margin-top: -1%;
            margin-bottom: 0%;

        }

        .contenido {
            padding-left: 80px;
            padding-right: 80px;
        }

        .jumbotron {
            margin-top: 0%;
            margin-bottom: 0%;
            padding-top: 4%;
            padding-bottom: 1%;
        }
    </style>
    <br>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a href="#"><img src="img/logoImplementtaHorizontal.png" width="250" height="82" alt=""></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="nav-item nav-link text-dark" href="#"> Inicio </a>
                <a class="nav-item nav-link text-dark" href="https://gallant-driscoll.198-71-62-113.plesk.page/implementta/modulos/Administrador/logout.php">
                    Salir <i class="fas fa-sign-out-alt"></i></a>
            </ul>
        </div>
    </nav>
    <br>
</head>

<body>


    <div class="container col-md-11 ">
        
        <div class="contenido">
        <div class="text-center">
            <h2 style="text-shadow: 0px 0px 2px #717171;"><img src="https://img.icons8.com/fluency/48/rtf-document.png"/> modulos</h2>
        </div>
        <hr>
            <?php while($modulos=sqlsrv_fetch_array($sql_modulos,SQLSRV_FETCH_ASSOC)){ ?>
                <a href="formatos.php?m=<?= $modulos['id'] ?>"><?= $modulos['nombre'] ?></a>
            <?php } ?>
        </div>
    </div>

    <nav class="navbar sticky-bottom navbar-expand-lg">
        <span class="navbar-text" style="font-size:12px;font-weigth:normal;color: #7a7a7a;">
            Implementta ©<br>
            Estrategas de México <i class="far fa-registered"></i><br>
            Centro de Inteligencia Informática y Geografía Aplicada CIIGA
            <hr style="width:105%;border-color:#7a7a7a;">
            Created and designed by <i class="far fa-copyright"></i> <?php echo date('Y') ?> Estrategas de México<br>
        </span>
        <hr>
        <span class="navbar-text" style="font-size:12px;font-weigth:normal;color: #7a7a7a;">
            Contacto:<br>
            <i class="fas fa-phone-alt"></i> Red: 187<br>
            <i class="fas fa-phone-alt"></i> 66 4120 1451<br>
            <i class="fas fa-envelope"></i> sistemas@estrategas.mx<br>
        </span>
        <ul class="navbar-nav mr-auto">
            <br><br><br><br><br><br><br><br>
        </ul>
        <form class="form-inline my-2 my-lg-0">
            <a href="#"><img src="img/logoImplementta.png" width="155" height="150" alt=""></a>
            <a href="http://estrategas.mx/" target="_blank"><img src="img/logoTop.png" width="200" height="85" alt=""></a>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </form>
    </nav>

</body>


</html>