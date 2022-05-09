<?php 
include './conexion/conexion.php';
include './librerias/fun_persistencia.php';
include './librerias/fun_servicios.php';
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
            <label>Seleccione el medio de pago:</label>
            <?php
            if (isset($_POST['servicio_id']) && isset($_POST['monto'])) {

                $servicio_id = $_POST['servicio_id'];
                $monto = $_POST['monto'];
                $concepto = $_POST['concepto'];
                $api_key = $_POST['apikey'];
                $api_url = $_POST['apiurl'];

                $idDeuda = $servicio_id;
                $siExiste = 'update';
                $apiUrl = $api_url;
                $apiKey = $api_key;

// Hora DEBE ser en UTC!
                $ahora = new DateTimeImmutable('now', new DateTimeZone('UTC'));
                $expira = $ahora->add(new DateInterval('P2D'));

// Crear modelo de la deuda
                $deuda = [
                    'docId' => $idDeuda,
                    'label' => $concepto,
                    'amount' => ['currency' => 'PYG', 'value' => $monto],
                    'validPeriod' => [
                        'start' => $ahora->format(DateTime::ATOM),
                        'end' => $expira->format(DateTime::ATOM)
                    ]
                ];

// Crear JSON para el post
                $post = json_encode(['debt' => $deuda]);

// Hacer el POST
                $curl = curl_init();

                curl_setopt_array($curl, [
                    CURLOPT_URL => $apiUrl,
                    CURLOPT_HTTPHEADER => ['apikey: ' . $apiKey, 'Content-Type: application/json', 'x-if-exists' => $siExiste],
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => $post
                ]);

                $response = curl_exec($curl);

                if ($response) {
                    $data = json_decode($response, true);

                    // Deuda es retornada en la propiedad "debt"
                    $payUrl = isset($data['debt']) ? $data['debt']['payUrl'] : null;
                    if ($payUrl) {
                        actualizarDatos("servicios", ["url_pago" => $payUrl], ["servicio_id[=]" => $idDeuda]);
                        echo "<p><a href='$payUrl' class='btn btn-success'>Ir a opciones de pago</a></p>";
                    } else {
//            echo "No se pudo crear la deuda\n";
                        echo "<p><a href='$payUrl' class='btn btn-warning'>No se pudo crear la deuda\n</a></p>";
                        echo "<p><a href='index.php' class='btn btn-default'>Volver al inicio</a></p>";
//                        print_r($data['meta']);
                    }
                } else {
                    echo 'curl_error: ', curl_error($curl);
                }
                curl_close($curl);
            } else {
                header("Location: index.php");
            }
            ?>

        </div>
    </body>
</html>