<?php
session_start();
unset($_SESSION['f']);
require "cnx/cnx.php";

if (isset($_GET['m']) and !empty($_GET['m'])) {
    $id_modulo = $_GET['m'];
} elseif (isset($_SESSION['m'])) {
    $id_modulo = $_SESSION['m'];
} else {
    header("Location: modulos.php");
    exit();
}
$_SESSION['m'] = $id_modulo;
$sql_formatos = sqlsrv_query($cnx, "SELECT row_number() OVER (ORDER BY id desc) as fila,* FROM formatos where id_modulo='$id_modulo'");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/pdf.css">
    <script src="js/alerta.js"></script>
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
    <?php
    if (isset($_SESSION['snDatos'])) {
        echo "<script>
        window.onload = function() {
            mostrarSweetAlert('error', 'Fatal error', '" . htmlspecialchars($_SESSION['snDatos']) . "');
        };
    </script>";

        // Limpiar el mensaje de error en sesión
        unset($_SESSION['snDatos']);
    }
    if (isset($_SESSION['error'])) {
        echo "<script>
        window.onload = function() {
            mostrarSweetAlert('error', 'Fatal error', '" . htmlspecialchars($_SESSION['error']) . "');
        };
    </script>";

        // Limpiar el mensaje de error en sesión
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo "<script>
        window.onload = function() {
            mostrarSweetAlert('success', 'En hora buena!', '" . htmlspecialchars($_SESSION['success']) . "');
        };
    </script>";

        // Limpiar el mensaje de error en sesión
        unset($_SESSION['success']);
    }
    ?>


    <div class="container col-md-11 ">

        <div class="contenido">
            <div class="text-center">
                <h2 style="text-shadow: 0px 0px 2px #717171;"><img src="https://img.icons8.com/fluency/48/rtf-document.png" /> Formatos de Guadalajara</h2>
            </div>
            <hr>

            <?php if (sqlsrv_has_rows($sql_formatos)) { ?>
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Formato</th>
                            <th scope="col">Fecha de creación</th>
                            <th scope="col">Ultima modificación</th>
                            <th scope="col">Archivo css</th>
                            <th scope="col">Archivo html</th>
                            <th scope="col">Archivo de fondo</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($formatos = sqlsrv_fetch_array($sql_formatos)) {
                            $id_formato = $formatos['id'];
                            $sql_commit = sqlsrv_query($cnx, "select top 1 fecha from commits where id_formato='$id_formato' order by id desc");
                            $fecha_commit = '---------------';
                            if (sqlsrv_has_rows($sql_commit)) {
                                $commit = sqlsrv_fetch_array($sql_commit);
                                $fecha_commit = $commit['fecha'];
                            }

                        ?>
                            <tr>
                                <th scope="row"><?= $formatos['fila'] ?></th>
                                <td><i class="fa-solid fa-file-pdf"></i> <?= $formatos['nombre'] ?></td>
                                <td><i class="fa-solid fa-calendar-days"></i> <?= $formatos['fechaC'] ?></td>
                                <td><i class="fa-solid fa-calendar-days"></i> <?= $fecha_commit ?></td>
                                <td>
                                    <button class="btn btn-info btnCss" data-id="<?= $formatos['id'] ?>" data-archivo="<?= $formatos['css'] ?>"> <i class="fa-solid fa-file"></i> Archivo CSS</button>
                                </td>
                                <td></td>
                                <td></td>
                                <td><a href="editor.php?f=<?= $formatos['id'] ?>" class="btn btn-primary"><i class="fa-solid fa-eye"></i> Editar</a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <div class="alert alert-danger" role="alert">
                    Este modulo aun no cuenta con ningun formato, para crear uno presione el boton de crear nuevo formato.
                </div>
            <?php } ?>

            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalFormato"><i class="fa-solid fa-file"></i> Crear nuevo formato</button>

        </div>
    </div>

    <!-- Modal nueva tarea -->
    <div class="modal fade" id="modalFormato" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear nuevo formato</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="CrearFormato.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del formato</label>
                            <input type="text" class="form-control" placeholder="Ingrese el nombre" id="nombre" name="nombre" required>
                        </div>

                        <div class="mb-3">
                            <label for="html" class="form-label">Carga tu archivo txt del html</label>
                            <input type="file" class="form-control" id="html" accept=".txt" name="html" required>
                        </div>
                        <div class="mb-3">
                            <label for="fondo" class="form-label">Carga tu archivo txt del header y footer</label>
                            <input type="file" class="form-control" id="fondo" accept=".txt" name="fondo" required>
                        </div>
                        <div class="mb-3">
                            <label for="css" class="form-label">Carga tu archivo txt de los estilos css</label>
                            <input type="file" class="form-control" id="css" accept=".txt" name="css" required>
                        </div>
                        <div class="mb-3">
                            <label for="tamanio" class="form-label">Selecciona el tamaño que tendra las hojas del pdf</label>
                            <select class="custom-select custom-select-sm mb-3" name="tamanio" required>
                                <option value="">--Seleccion una opción--</option>
                                <option value="carta">Carta</option>
                                <option value="oficio">Oficio</option>
                            </select>
                        </div>

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="btnCrearTarea">Crear formato</button>
                    </form>
                </div>

            </div>
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
    <!-- modal para el css -->
    <div class="modal fade" id="modalCss" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Archivo CSS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="CrearFormato.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="css" class="form-label">Carga tu archivo txt de los estilos css</label>
                            <input type="file" class="form-control" id="css" accept=".txt" name="css" required>
                        </div>
                        <div class="mb-3">
                            
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <a id="btnDescargaCss" href="" download class="btn btn-success">Descargar archivo actual</a>
                        <button type="submit" class="btn btn-primary" id="btnCrearTarea">Reemplazar archivo</button>
                    </form>
                </div>

            </div>
        </div>
    </div>


    <script>
        $('.btnCss').click(function() {
            // Obtener el ID y el porcentaje de CSS del botón
            var id = $(this).data('id');
            var archivo = $(this).data('archivo');
            console.log(id, '-', archivo);
            $('#btnDescargaCss').attr('href', archivo);
            $("#modalCss").modal("show");


        });
    </script>
    </script>
    <script>
        document.getElementById('html').addEventListener('change', validateFileExtension);
        document.getElementById('fondo').addEventListener('change', validateFileExtension);
        document.getElementById('css').addEventListener('change', validateFileExtension);

        function validateFileExtension(event) {
            const allowedExtensions = ['txt'];
            const input = event.target;
            const fileName = input.files[0].name;
            const fileExtension = fileName.split('.').pop().toLowerCase();
            console.log(fileExtension);

            if (!allowedExtensions.includes(fileExtension)) {
                Swal.fire({
                    icon: "error",
                    title: "Fatal error!",
                    text: "El archivo debe de ser con extensión .txt",
                    timer: 3000, // Duración en milisegundos (3 segundos en este caso)
                    showConfirmButton: false // Ocultar el botón de confirmación
                });
                input.value = ''; // Clear the input
            }
        }
    </script>


</html>