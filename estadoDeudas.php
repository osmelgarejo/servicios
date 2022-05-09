<?php
include './conexion/conexion.php';
include './librerias/fun_persistencia.php';
include './librerias/fun_servicios.php';

if (isset($_POST['cedula'])) {

    $cedula = $_POST['cedula'];

    $sql = "SELECT 
	s.servicio_id AS nro,
	s.fecha,
	c.nombre,
	co.concepto,
	s.monto,
        s.url_pago
FROM 
	servicios s
JOIN clientes c ON c.cliente_id = s.cliente_id
JOIN conceptos co ON co.concepto_id = s.concepto_id
WHERE
	c.cedula = $cedula  order by 1 asc";

    $datosServicios = obtenerDatosQuery($sql);

    //datos para ws
    $sql = "SELECT api_key, api_url FROM parametros LIMIT 1";
    $datosParametros = obtenerDatosQuery($sql);

    if (isset($datosParametros)) {
        foreach ($datosParametros as $row) {
            $api_key = $row['api_key'];
            $api_url = $row['api_url'];
        }
    }
    
    
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
            <label>Estado de las deudas:</label>          

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nro.</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Nombre</th>
                        <th>Concepto</th>
                        <th>Monto</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody>                                       
                    <?php
                    if (isset($datosServicios)) {
                        foreach ($datosServicios as $row) {
                            
                            $estadoDeuda = recuperarEstadoDeudas($row['nro'], $api_url, $api_key);
                            
                            echo "<tr><td>" . $row['nro'] . "</td><td>" . $row['fecha'] . "</td><td>" . $estadoDeuda ."</td><td>" . $row['nombre'] . "</td><td>" . $row['concepto'] . "</td><td>" . $row['monto'] . "</td>"
                            . "<td><form action='". $row['url_pago'] ."' method='POST'>"
                            . "<input type='hidden' name='servicio_id' value='" . $row['nro'] . "'>"
                            . "<input type='hidden' name='monto' value='" . $row['monto'] . "'>"
                            . "<input type='hidden' name='concepto' value='" . $row['concepto'] . "'>"
                            . "<input type='hidden' name='apikey' value='" . $api_key . "'>"
                            . "<input type='hidden' name='apiurl' value='" . $api_url. "'>"
                            . "<input type='submit' class='btn btn-success' name='crear' value='Pagar' ".bloquearBotonEstado($estadoDeuda).">"
                            . "</form></td></tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>            
            <p><button type="button" onclick="location.href = 'index.php'" class="btn btn-default">Cancelar</button></p>
        </div>
    </body>
</html>
