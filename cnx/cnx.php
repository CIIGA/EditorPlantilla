<?php
$serverName = "implementta.mx";
    $connectionInfo = array( 'Database'=>'tiketWeb', 'UID'=>'sa', 'PWD'=>'vrSxHH3TdC');
    $cnx = sqlsrv_connect($serverName, $connectionInfo);
    date_default_timezone_set('America/Mexico_City');
?>