<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
require_once "../autoload.php";

//inicializar controlador
use app\controllers\jobController;

$insformulario = new jobController();


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['imagen'])) {
    $tarea_id = $_POST['idservicio_imagen'];
    $imagen = $_FILES['imagen'];

    // Definir directorio de almacenamiento
    $directorio = "../subirimg/";
    if (!is_dir($directorio)) {
        mkdir($directorio, 0777, true);
    }

    // Generar nombre único

    $nombreOriginal = pathinfo($imagen["name"], PATHINFO_FILENAME);
    $extension = pathinfo($imagen["name"], PATHINFO_EXTENSION);

    // Eliminar espacios y caracteres especiales como #, %, &, etc.
    $nombreLimpio = preg_replace('/[^A-Za-z0-9_-]/', '_', $nombreOriginal);
    
    // Generar un nombre único
    $nombreImagen = $nombreLimpio . "_" . time() . "." . $extension;
 
    $rutaImagen = $directorio . $nombreImagen;

     if (move_uploaded_file($imagen["tmp_name"], $rutaImagen)) {
        $stmt = $insformulario->subirimagen($tarea_id , $nombreImagen, $rutaImagen); 
          $alerta = [
            "classform" => ".FormularioAjax4",
            "tipo" => "limpiar",
            "titulo" => "Image",
            "texto" => "The record was saved successfully",
            "icono" => "success",
            "idgenerado" => $stmt
        ];
    } else {
          $alerta = [
            "classform" => ".FormularioAjax4",
            "tipo" => "limpiar",
            "titulo" => "Image",
            "texto" => "Error uploading image.",
            "icono" => "error"
        ];
    }

    echo json_encode($alerta);
}


//Metodo GET para la carga del grid en la pantalla
if (isset($_GET['cargarimagenes'])) {
    //metodo del controlador
    $result = $insformulario->listarimagenes();
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
            'message' =>  'No information found'
        );
        echo json_encode($res);
    }
}


//Metodo GET para la carga del grid en la pantalla
if (isset($_GET['eliminar'])) {
    //metodo del controlador
    $result = $insformulario->eliminarimagen();
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
            'message' =>  'No information found'
        );
        echo json_encode($res);
    }
}