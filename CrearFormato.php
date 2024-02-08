<?php
session_start();
if (!(isset($_FILES['html'])) || !(isset($_POST['nombre'])) || !(isset($_POST['tamanio'])) || !(isset($_FILES['css'])) || !(isset($_FILES['fondo'])) || !(isset($_SESSION['m']))) {
    $_SESSION['snDatos'] = 'Parámetros incompletos para continuar con el proceso';
    header("Location: formatos.php");
    exit();
}

require "cnx/cnx.php";

try {
    // Iniciar la transacción
    sqlsrv_begin_transaction($cnx);

    $nombre = $_POST['nombre'];
    $tamanio = $_POST['tamanio'];
    $html = $_FILES['html']['tmp_name'];
    $css = $_FILES['css']['tmp_name'];
    $fondo = $_FILES['fondo']['tmp_name'];
    $id_modulo = $_SESSION['m'];

    // Validar si no existe un formato con el mismo nombre en este modulo
    $sql_formato = sqlsrv_query($cnx, "SELECT f.id FROM modulos AS m INNER JOIN formatos AS f ON m.id = f.id_modulo WHERE m.id = '$id_modulo' and f.nombre='$nombre'");

    if (sqlsrv_has_rows($sql_formato)) {
        throw new Exception('El nombre del formato introducido ya existe en este modulo');
    }

    // Obtener el nombre del modulo en el que está, ya que así se llama la carpeta de las plantillas
    $sql_modulo = sqlsrv_query($cnx, "SELECT * FROM modulos WHERE id='$id_modulo'");
    $modulo = sqlsrv_fetch_array($sql_modulo);
    $Nmodulo = $modulo['nombre'];

    // Ubicacion de la carpeta del modulo
    $moduloPath = 'C:/wamp64/www/EditorPlantilla/plantillas/' . $Nmodulo;

    // Directorio de la carpeta del formato
    $formatoPath = $moduloPath . '/' . $nombre;



    // Crear una carpeta del formato
    if (!mkdir($formatoPath, 0755)) {
        throw new Exception('Error al crear el formato, comuniquese con soporte.');
    }

    $directorioHtml = $formatoPath . '/html.txt';
    $directorioCss = $formatoPath . '/css.txt';
    $directorioFondo = $formatoPath . '/fondo.txt';

    // Cargar los archivos
    cargar($directorioHtml, $html);
    cargar($directorioCss, $css);
    cargar($directorioFondo, $fondo);

    $urlHtml='http://'.$_SERVER['HTTP_HOST'].'/EditorPlantilla/plantillas/'.$Nmodulo.'/'.$nombre.'/html.txt';
    $urlCss='http://'.$_SERVER['HTTP_HOST'].'/EditorPlantilla/plantillas/'.$Nmodulo.'/'.$nombre.'/css.txt';
    $urlFondo='http://'.$_SERVER['HTTP_HOST'].'/EditorPlantilla/plantillas/'.$Nmodulo.'/'.$nombre.'/fondo.txt';



    $timeInsert = date('Y-m-d') . ' ' . date('H:i:s');

    // Cargar los datos a la base de datos
    $sql_insert = sqlsrv_query($cnx, "INSERT INTO formatos(nombre,fechaC,html,css,fondo,id_modulo,tamanio) 
    VALUES ('$nombre','$timeInsert','$urlHtml','$urlCss','$urlFondo','$id_modulo','$tamanio')");

    if (!($sql_insert)) {
        throw new Exception('Error al insertar el formato, comuniquese con soporte.');
    }


    $sql_formato_inserted = sqlsrv_query($cnx, "select id from formatos where nombre='$nombre' and id_modulo='$id_modulo'");
    $formato_inserted = sqlsrv_fetch_array($sql_formato_inserted);
    $id_formato = $formato_inserted['id'];
    //crear archivo php del pdf
    $nombreArchivoTxt = 'plantillaPdf.txt';

    // Lee el contenido del archivo de texto
    $contenidoTxt = file_get_contents($nombreArchivoTxt);
    $contenidoTxt = str_replace('$v_formato', $id_formato, $contenidoTxt);
    // Ruta del directorio específico donde deseas guardar el nuevo archivo PHP
    $rutaPhp = $formatoPath . '/';

    // Nombre del archivo PHP que deseas crear
    $nombreArchivoPHP = 'pdf.php';

    // Escribe el contenido en el nuevo archivo PHP en el directorio específico
    $pdfPhp = file_put_contents($rutaPhp . $nombreArchivoPHP, $contenidoTxt);
    if (!$pdfPhp) {
        throw new Exception('Error en generar el pdf, comuniquese con soporte.');
    }
    //crear el archivo de segunda version
    $nombreArchivoPHP = 'htmlv2.txt';
    $v2 = touch($rutaPhp . $nombreArchivoPHP);
    if (!$v2) {
        throw new Exception('Error un archivo, comuniquese con soporte.');
    }
    // Commit de la transacción
    sqlsrv_commit($cnx);

    $_SESSION['success'] = 'Formato creado correctamente.';
    header("Location: formatos.php");
    exit();
} catch (Exception $e) {
    // Rollback en caso de error
    sqlsrv_rollback($cnx);

    // Eliminar la carpeta del formato y sus archivos
    if (isset($formatoPath) && is_dir($formatoPath)) {
        eliminarDirectorio($formatoPath);
    }

    // Almacenar el mensaje de error en la sesión
    $_SESSION['error'] = $e->getMessage();

    // Redireccionar a la página de error
    header("Location: formatos.php");
    exit();
}

// Función para cargar los archivos del formato
function cargar($directorio, $archivo)
{
    move_uploaded_file($archivo, $directorio);
}

// Función para eliminar un directorio y su contenido recursivamente
function eliminarDirectorio($directorio)
{
    $archivos = glob($directorio . '/*');
    foreach ($archivos as $archivo) {
        is_dir($archivo) ? eliminarDirectorio($archivo) : unlink($archivo);
    }
    rmdir($directorio);
}
