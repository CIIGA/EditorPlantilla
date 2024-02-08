<?php
ob_start();
require "../cnx/cnx.php";
$id_formato = 1;
// validar si el formato pertenece al modulo
$sql_formato = sqlsrv_query($cnx, "select * from formatos where id='$id_formato'");

$formatos = sqlsrv_fetch_array($sql_formato);
$html = $formatos['html'];
$css = $formatos['css'];
$fondo = $formatos['fondo'];
$tamanio=$formatos['tamanio'];
$contenido = file_get_contents($html);
$contenidoCss = file_get_contents($css);
$contenidoFondo = file_get_contents($fondo);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $formatos['nombre'] ?></title>
    <style>
        <?= $contenidoCss ?>
    </style>
</head>


<body>
    <?= $contenidoFondo ?>

    <?php echo $contenido ?>
</body>

</html>
<?php
//guardar tod0 el buher en una variable
$html = ob_get_clean();


require_once "dompdf/autoload.inc.php";

use Dompdf\Dompdf;

$pdf = new Dompdf();

$options = $pdf->getOptions();
$options->set(array("isRemoteEnabled" => true));
$pdf->set_option("isPhpEnabled", true);
$pdf->setOptions($options);

$pdf->loadHtml($html);
$tipo='letter';
if ($tamanio=='oficio') {
    $tipo='legal';
}
$pdf->setPaper('A4', 'legal');
// horizontal
// $dompdf->setPaper('A4', 'landscape'); 
$pdf->render();
// true para que habra el pdf
// false para que se descargue
$pdf->stream($formatos['nombre'].".pdf", array("Attachment" => false));
?>