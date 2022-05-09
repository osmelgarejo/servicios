<?php

function obtenerDatosQuery($sql) {

    global $db;

    $data = $db->query($sql)->fetchAll();

    if (isset($data)) {//EN CASO DE RETORNAR DATOS
        foreach ($data as $row) {
            $t[] = $row;
        }
        if (isset($t)) {//SI LA VARIABLE t CONTIENE DATOS
            return $t; //RETORNAMOS LA VARIABLE t
        } else {//SI LA VARIABLE t NO CONTIENE DATOS
            return null; //RETORNAMOS NULO
        }
    }
}

function insertarDatos($tabla, $campo_id, $datos, $es_autonumerico) {

    global $db;

    //validamos si el campo pk es autonumerico
    if ($es_autonumerico == 'NO') {

        //recuperamos el valor para el id
        $ultimo_id = recuperarUltimoID($tabla, $campo_id);

        //asignamos al array datos el id generado
        $datos[$campo_id] = $ultimo_id;

        //insertamos en la tabla
        $db->insert($tabla, [
            $datos
        ]);
    } else {

        $db->insert($tabla, [
            $datos
        ]);
    }


    if ($db->error()[1] === NULL) {
//        var_dump($db->log());
//        echo '<br>';
//        echo 'Exito';
        return true;
    } else {
//        var_dump($db->log());
//        echo '<br>';
//        var_dump($db->error());
        return false;
    }
}

function actualizarDatos($tabla, $datos, $condicion) {

    global $db;

    $data = $db->update($tabla, $datos, $condicion);

    if ($db->error()[1] === NULL) {

        $registros_afectados = $data->rowCount();

        if ($registros_afectados > 0) {
//            echo 'Registros afectados: ' . $registros_afectados;
//            echo 'Exito upt';
        } else {
//            echo 'Registros afectados: ' . $registros_afectados;
        }
        return true;
    } else {
//        var_dump($db->log());
//        echo '<br>';
//        var_dump($db->error());
        return false;
    }
}


function eliminarDatos($tabla, $condicion) {

    global $db;

    $data = $db->delete($tabla, [
        "AND" =>
        $condicion
    ]);

    if ($db->error()[1] === NULL) {

        $registros_afectados = $data->rowCount();

        if ($registros_afectados > 0) {
//            echo 'Registros afectados: ' . $registros_afectados;
//            echo 'Exito upt';
        } else {
//            echo 'Registros afectados: ' . $registros_afectados;
        }
        return true;
    } else {
//        var_dump($db->log());
//        echo '<br>';
//        var_dump($db->error());
        return false;
    }
}

//genera una lista desplegable a partir de los parametros ingresados como tabla
function generarListaDesplegable($tabla, $codigoNombre, $nombre, $tipo, $codigoValor, $inputName, $condicion) {


    if ($tipo == 'agregar') {

        $sql = "select $codigoNombre as id, $nombre as nombre from $tabla $condicion order by $nombre asc";
        $res = obtenerDatosQuery($sql);
        
        echo '<select  class="form-control" name="' . $inputName . '" id="' . $inputName . '" required>';
        foreach ($res as $data) {
            echo '<option value=' . $data[id] . '>' . utf8_encode($data[nombre]) . '</option>"';
        }
        echo '</select>';
    }

    if ($tipo == 'editar') {
        $sql = "select $codigoNombre as id, $nombre as nombre from $tabla $condicion order by $codigoNombre asc";
        $res = obtenerDatosQuery($sql);

        echo '<select class="chosen-select" name="' . $inputName . '"  required>';
        foreach ($res as $data) {

            //si el valor registrado es igual a uno de los registros guardados en la BD
            //entonces mantener seleccionado
            if ($data[id] == $codigoValor) {
                echo '<option value=' . $data[id] . ' selected="selected">' . utf8_encode($data[nombre]) . '</option>"';
            } else {
                echo '<option value=' . $data[id] . '>' . utf8_encode($data[nombre]) . '</option>"';
            }
        }
        echo '</select>';
    }
}

