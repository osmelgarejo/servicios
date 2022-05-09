<?php

//valida si ya existe el cliente

function validarExistenciaCliente($cedula) {

    global $db;

    if (!isset($cedula)) {
        return false;
    }

    $sql = "select count(*) as cantidad from clientes where cedula = $cedula";

    $data = $db->query($sql)->fetchAll();
    foreach ($data as $row) {

        $cantidad = $row['cantidad'];

        if ($cantidad > 0) {
            return true;
        }
    }
    return false;
}

//recuperarNombre
function recuperarNombre($cedula) {

    global $db;

    if (!isset($cedula)) {
        return false;
    }

    $sql = "select nombre from clientes where cedula = $cedula";

    $data = $db->query($sql)->fetchAll();
    foreach ($data as $row) {
        $nombre = $row['nombre'];
    }
    return $nombre;
}

//inserta nuevo cliente
function insertarCliente($cedula, $nombre, $correo) {

    if (!isset($cedula) || !isset($nombre) || !isset($correo)) {
        return false;
    }

    $es_insertado = insertarDatos("clientes", "cliente_id", [
        'cedula' => $cedula,
        'nombre' => $nombre,
        'correo' => $correo
            ], "SI");

    return $es_insertado;
}

//inserta nuevo servicio a pagar
function insertarServiciosAPagar($cedula, $concepto_id) {

    if (!isset($cedula) || !isset($concepto_id)) {
        return false;
    }

    $fecha = date('Y-m-d', time());
    $cliente_id = recuperarClienteID($cedula);
    $monto = recuperarMontoConcepto($concepto_id);

    $es_insertado = insertarDatos("servicios", "servicio_id", [
        'cliente_id' => $cliente_id,
        'concepto_id' => $concepto_id,
        'estado' => 'PENDIENTE',
        'monto' => $monto,
        'fecha' => $fecha
            ], "SI");

    return $es_insertado;
}

//recuperar cliente id por cedula
function recuperarClienteID($cedula) {

    global $db;

    if (!isset($cedula)) {
        return false;
    }

    $sql = "select cliente_id from clientes where cedula = $cedula";

    $data = $db->query($sql)->fetchAll();
    foreach ($data as $row) {
        $cliente_id = $row['cliente_id'];
    }
    return $cliente_id;
}

//recuperar monto por concepto
function recuperarMontoConcepto($concepto_id) {

    global $db;

    if (!isset($concepto_id)) {
        return false;
    }

    $sql = "select monto from conceptos where concepto_id = $concepto_id";

    $data = $db->query($sql)->fetchAll();
    foreach ($data as $row) {
        $monto = $row['monto'];
    }
    return $monto;
    }

//RECUPERA EL ESTADO DE LAS DEUDAS GENERADAS
function recuperarEstadoDeudas($servicio_id, $apiurl, $apikey){

    $idDeuda = $servicio_id;
    $apiUrl = $apiurl .'/'.$idDeuda;
    $apiKey = $apikey;

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $apiUrl,
        CURLOPT_HTTPHEADER => ['apikey: ' . $apiKey],
        CURLOPT_RETURNTRANSFER => true
    ]);

    $response = curl_exec($curl);
    if ($response) {
        $data = json_decode($response, true);

        // Verificar estado de pago
        $debt = isset($data['debt']) ? $data['debt'] : null;
        if ($debt) {
            $payUrl = $debt['payUrl'];
            $label = $debt['label'];
            $objStatus = $debt['objStatus']['status'];
            $payStatus = $debt['payStatus']['status'];
            $isActive = false !== array_search($objStatus, ['active', 'alert', 'success']);
            $isPaid = $payStatus === 'paid';

//            echo "Deuda encontrada, URL=$payUrl\n";
//            echo "Concepto: $label\n";
//            echo "Activa: ", ($isActive ? 'Si' : 'No'), "\n";
//            echo "Pagada: ";
            if ($isPaid) {
                $payTime = $debt['payStatus']['time'];
                return 'PAGADA';
//                echo "Si, en fecha $payTime\n";
            } else {
                return 'PENDIENTE';
//                echo "No\n";
            }
        } else {
            return 'NO ENCONTRADA';
//            echo "No se pudo obtener datos de la deuda\n";
//            print_r($data['meta']);
        }
    } else {
        echo 'curl_error: ', curl_error($curl);
    }
    curl_close($curl);
}

//enviar la orden al boton para desactivarlo o no
function bloquearBotonEstado($estado){
    if ($estado == '' || $estado == "PAGADA" || $estado == "NO ENCONTRADA") {
         return "disabled='true'";       
    }
    return '';
}