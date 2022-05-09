<?php
include './conexion/conexion.php';
include './librerias/fun_persistencia.php';
include './librerias/fun_servicios.php';

if (isset($_POST['cedula'])) {

    $cedula = $_POST['cedula'];

    $existeCliente = validarExistenciaCliente($cedula);
} else {
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="jumbotron">
                <h1>Servipay</h1>
                <p>Pague sus servicios de forma facil y rapida</p>
            </div>

            <?php
            if ($existeCliente) {

                $nombre = recuperarNombre($cedula);
                ?>
                
                <form action="registrarPagoServicio.php" method="post">
                    <div class="form-group">
                        <input type="hidden" value="<?php echo $cedula?>" name="cedula">
                        <label >Seleccione el servicio que desea pagar</label>
                        <?php
                        generarListaDesplegable("conceptos", "concepto_id", "concepto", "agregar", "concepto_id", "concepto_id", "");
                        ?>
                    </div>
                    <button type="submit" class="btn btn-success">Siguiente</button>
                </form>

                <?php
            } else {
                ?>              
                <label>Ingrese sus datos basicos para registrarse</label>
                <form action="registrarCliente.php" method="post">
                    <div class="form-group">
                        <label >Cedula</label>
                        <input type="text" class="form-control" name="cedula" required="true" placeholder="5212014" value="<?php echo $cedula; ?>" onfocus="this.blur()">
                    </div>
                    <div class="form-group">
                        <label >Nombre</label>
                        <input type="text" class="form-control" name="nombre" required="true" placeholder="Juan Perez">
                    </div>
                    <div class="form-group">
                        <label >Correo</label>
                        <input type="email" class="form-control" name="correo" required="true" placeholder="jperez@gmail.com">
                    </div>
                    <button type="submit" class="btn btn-success">Registrar</button>

                </form>
                <?php
            }
            ?>
            <p><button type="button" onclick="location.href = 'index.php'" class="btn btn-default">Cancelar</button></p>
        </div>
    </body>
</html>
