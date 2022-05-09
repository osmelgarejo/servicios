<?php
include './conexion/conexion.php';
include './librerias/fun_persistencia.php';

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
            <label>Ingrese sus datos para proceder al pago del servicio</label>
            <form action="desicionCliente.php" method="post">
                <div class="form-group">
                    <label >Numero de Cedula</label>
                    <input type="number" class="form-control" name="cedula" required="true" placeholder="Ej: 5212014">
                </div>
                <button type="submit" class="btn btn-success">Consultar</button>
            </form>
        </div>




    </body>
</html>
