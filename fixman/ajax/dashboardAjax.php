<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
require_once('../tcpdf/examples/tcpdf_include.php');
require_once "../autoload.php";

//inicializar controlador
use app\controllers\jobController;

$insformulario = new jobController();

//Metodo GET para la carga del grid en la pantalla
if (isset($_GET['cargarestadostrabajo'])) {
    //metodo del controlador
    $result = $insformulario->listardashboard();
    //resultado que se envita al Metodo GET invocado
    if ($result) {
        $res = array(
            'status' => 200,
            'message' => 'Successful data upload',
            'data' => $result
        );
        echo json_encode($res);
    } else {
        $res = array(
            'status' => 404,
            'message' =>  'No se encontro informacion'
        );
        echo json_encode($res);
    }
}