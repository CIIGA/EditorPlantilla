<?php
session_start();
require "cnx/cnx.php";

if (isset($_GET['f']) and !empty($_GET['f']) and isset($_SESSION['m'])) {
    $id_formato = $_GET['f'];
} elseif (isset($_SESSION['f']) and isset($_SESSION['m'])) {
    $id_formato = $_SESSION['f'];
} else {
    header("Location: formatos.php");
    exit();
}
$_SESSION['f']=$id_formato;
$id_modulo = $_SESSION['m'];
// validar si el formato pertenece al modulo
$sql_validar = sqlsrv_query($cnx, "select f.* from formatos as f inner join modulos as m
on f.id_modulo=m.id where m.id='$id_modulo' and f.id='$id_formato'");

if (!sqlsrv_has_rows($sql_validar)) {
    $_SESSION['error'] = 'Acceso denegado';
    header("Location: formatos.php");
    exit();
}

$formatos = sqlsrv_fetch_array($sql_validar);
$html = $formatos['html'];
$css = $formatos['css'];
$fondo = $formatos['fondo'];
$contenido = file_get_contents($html);
$contenidoCss = file_get_contents($css);

//consultamos si tiene una version anterior
$sql_v2Formato = sqlsrv_query($cnx, "select top 1 * from formatosVA where id_formato='$id_formato'");

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

    <!-- summernote -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <!-- ------------------- -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>Editor de Plantilla</title>
    <style>
        <?= $contenidoCss ?>
    </style>
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

<body style="margin-bottom: -1%;">


    <div class="container col-md-11 ">
        <div class="text-center">
            <h2 style="text-shadow: 0px 0px 2px #717171;">Editor de plantillas de Guadalajara</h2>
            <h4 style="text-shadow: 0px 0px 2px #717171;"><img src="https://img.icons8.com/color/40/000000/signing-a-document.png" /> Municipio de xxxxxxxxxxx</h4>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <form id="formEditor" action="actualizarPlantilla.php" method="post">
                    <div class="mb-3">
                        <textarea id="summernote" name="editordata" class="form-control">
                        <?= $contenido ?>
                    </textarea>
                    </div>
                    <button type="button" onclick="mostrarConfirmacion()" target="_blank" class="btn btn-success"><img src="https://img.icons8.com/fluency/24/update-left-rotation--v1.png" /> Actualizar</button>
                    <?php if (sqlsrv_has_rows($sql_v2Formato)) { ?>
                        <button type="button" class="btn btn-warning" onclick="confirmarRegreso()">
                            <img src="https://img.icons8.com/fluency/24/update-left-rotation--v1.png" /> Regresar versión anterior
                        </button>
                    <?php } ?>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#camposModal">
                        Campos disponibles
                    </button>
                    <a href="formatos.php" class="btn btn-dark btn-sm "><i class="fas fa-angle-left"></i> Regresar</a>
                </form>
            </div>
            <div class="col-md-6">
                <script>
                    Swal.fire({
                        title: 'Cargando...',
                        html: 'Espere por favor',
                        icon: 'info',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                </script>
                <iframe id="FramePdf" src="pdf/pdf.php?f=<?= $id_formato ?>" width="100%" height="600px" frameborder="0"></iframe>

                <script>
                    // Oculta SweetAlert cuando el iframe ha terminado de cargar
                    document.getElementById('FramePdf').addEventListener('load', function() {
                        Swal.close();
                    });
                </script>
            </div>
        </div>
        <hr>

    </div>
    <div class="modal fade" id="camposModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Campos disponibles</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="max-height: 600px; overflow-y: auto;">
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Campo</th>
                                <th scope="col">Nomenclatura</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>nombre</td>
                                <td>v_nombre</td>
                            </tr>
                           

                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar bottom navbar-expand-lg">
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
    <script>
    function confirmarRegreso() {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esto revertirá a una versión anterior. ¿Deseas continuar?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, regresar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirigir a tu archivo PHP con la variable en GET
                window.location.href = 'regresar.php';
            }
        });
    }
</script>
    
<script>
 function mostrarConfirmacion() {
    Swal.fire({
        title: '¿Estás seguro?',
        html: '<div class="form-group">' +
                  '<label for="swal-input1" class="form-label">Esta acción actualizara la plantilla, Si desea continuar describa los cambios realizados al formato</label>' +
                  '<textarea id="swal-input1" class="form-control" rows="4" name="cambiosRealizados" placeholder="Describe los cambios" required></textarea>' +
              '</div>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, guardar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Si se confirma, enviar el formulario
            const cambiosRealizados = document.getElementById('swal-input1').value;
            if (cambiosRealizados.trim() === "") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Debe de describir que cambios realizó al formato',
                    });
                    return;
                }
            // Crear un elemento input para agregarlo al formulario y enviarlo
            const inputHidden = document.createElement("input");
            inputHidden.type = "hidden";
            inputHidden.name = "cambiosRealizados";
            inputHidden.value = cambiosRealizados;
            document.getElementById('formEditor').appendChild(inputHidden);

            // Enviar el formulario
            document.getElementById('formEditor').submit();
        }
    });
}
</script>

    <script>
        $('#summernote').summernote({
            placeholder: 'Hello Bootstrap 4',
            tabsize: 2,
            height: 500,
            with: 500
        });
    </script>
</body>


</html>