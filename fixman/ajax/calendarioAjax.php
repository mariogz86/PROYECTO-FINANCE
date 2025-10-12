<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
require_once "../autoload.php";



//incializando controlador
use app\controllers\calendarioController;

$inscatalogo = new calendarioController();

//Metodo GET para la carga del grid en la pantalla
if (isset($_GET['cargagrid'])) {
    //llamada al metodo de carga del controlador
    $result = $inscatalogo->listarcatalogo();

    // Definir colores según el estado del trabajo
    $colores = [
        'Diagnosed' => '#f39c12', // naranja
        'Pending' => '#040b10ff', // azul
        'Booked' => '#a27ef6ff', // verde
        'Cancelled' => '#e74c3c', // rojo
        'Accepted' => '#025a83ff', // rojo
        'complete' => '#01ad51ff', // rojo


    ];

    foreach ($result as $row) {
        // Determinar color según estado
        $color = isset($colores[$row['estado']]) ? $colores[$row['estado']] : '#95a5a6';
        $partes = explode("-", $row['num_referencia']);

        // Agregar al arreglo de eventos
        $events[] = [
            'id' => $row['id_cita'],
            'title' => 'JOB-' . $partes[1], // título breve
            'jobref' => $row['num_referencia'],
            'start' => $row['fecha'],
            'nota' =>  $row['nota'], // resumen corto
            'estado' => $row['estado'],
            'color' => $color,
            'allDay' => true
        ];
    }

    echo json_encode($events);

    // //resultado que se envia al metodo GET
    // if ($result) {
    //     $res = array(
    //         'status' => 200,
    //         'message' => 'Successful data upload',
    //         'data' => $result
    //     );
    //     echo json_encode($res);
    // } else {
    //     $res = array(
    //         'status' => 404,
    //         'message' =>  'No information found'
    //     );
    //     echo json_encode($res);
    // }
}
