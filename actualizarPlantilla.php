<?php
session_start();
require "cnx/cnx.php";

if (!(isset($_POST['cambiosRealizados'])) || !(isset($_POST['editordata']))) {
    $_SESSION['error'] = 'Parámetros incompletos para continuar con el proceso';
    header("Location: editor.php");
    exit();
}
if ((!isset($_SESSION['f']))) {
    header("Location: formatos.php");
}
$id_modulo=$_SESSION['m'];
$id_formato=$_SESSION['f'];
$cambiosRealizados=$_POST['cambiosRealizados'];

$contenido = $_POST['editordata'];

// Obtener el nombre del modulo en el que está, ya que así se llama la carpeta de las plantillas
$sql_modulo = sqlsrv_query($cnx, "SELECT * FROM modulos WHERE id='$id_modulo'");
$modulo = sqlsrv_fetch_array($sql_modulo);
$Nmodulo = $modulo['nombre'];

// Obtener el nombre del formato en el que está, ya que así se llama la carpeta de las plantillas
$sql_formato = sqlsrv_query($cnx, "SELECT * FROM formatos WHERE id='$id_formato'");
$formato = sqlsrv_fetch_array($sql_formato);
$Nformato = $formato['nombre'];

// Ubicacion de la carpeta del modulo
$moduloPath = 'C:/wamp64/www/EditorPlantilla/plantillas/' . $Nmodulo;

// Directorio de la carpeta del formato
$formatoPath = $moduloPath . '/' . $Nformato;

//extraer el contenido del html actual y mandarlo al html de la segunda version
$contenidoHtml = file_get_contents($formatoPath.'/html.txt');
$resultado = file_put_contents($formatoPath.'/htmlv2.txt', $contenidoHtml);
// ahora el contenido extraido del post se manda al actual
$resultado = file_put_contents($formatoPath.'/html.txt', $contenido);
$timeInsert = date('Y-m-d') . ' ' . date('H:i:s');
// insertamos los registros al sql
$sql_insert_commit=sqlsrv_query($cnx,"insert into commits (descripcion,fecha,id_formato) values ('$cambiosRealizados','$timeInsert','$id_formato')");
$deleteVA=sqlsrv_query($cnx,"delete from formatosVA where id_formato='$id_formato'");
$sql_insert_formatoVA=sqlsrv_query($cnx,"insert into formatosVA (id_formato,fecha) values ('$id_formato','$timeInsert')");

// header("Location: formulario.php");
echo '<meta http-equiv="refresh" content="0;url=editor.php">';

?>
