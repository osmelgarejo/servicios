<?php

include './conexion/conexion.php';
include './librerias/fun_persistencia.php';
include './librerias/fun_servicios.php';

if (isset($_POST['cedula']) && isset($_POST['nombre']) && isset($_POST['correo'])) {

    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];

    $existeCliente = validarExistenciaCliente($cedula);

    if (!$existeCliente) {
        $insertado = insertarCliente($cedula, $nombre, $correo);

        if ($insertado) {
            header("Location: index.php");
        } else {
            header("Location: index.php");
        }
    } else {
        header("Location: index.php");
    }
} else {
    header("Location: index.php");
}
?>
