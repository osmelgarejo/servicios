<?php

include './conexion/conexion.php';
include './librerias/fun_persistencia.php';
include './librerias/fun_servicios.php';

if (isset($_POST['cedula']) && isset($_POST['concepto_id'])) {

    $cedula = $_POST['cedula'];
    $concepto_id = $_POST['concepto_id'];

    $insertado = insertarServiciosAPagar($cedula, $concepto_id);
    
    header("Location: resumenPago.php?cedula=$cedula");
} else {
    header("Location: index.php"); 
}
?>
