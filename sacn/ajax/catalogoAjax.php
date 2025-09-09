<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
require_once "../autoload.php";

use app\controllers\logerrorController;

$log = new logerrorController();


//incializando controlador
use app\controllers\catalogoController;

$inscatalogo = new catalogoController();

//Metodo GET para la carga del grid en la pantalla
if (isset($_GET['cargagrid'])) {
	try {
		//llamada al metodo de carga del controlador
		$result = $inscatalogo->listarcatalogo();

		//resultado que se envia al metodo GET
		if ($result) {
			$res = array(
				'status' => 200,
				'message' => 'carga usuarios correcta',
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
	} catch (Exception  $e) {
		$res = array(
			'status' => 404,
			'message' =>  'No se encontro informacion'
		);

		$log->guardarlog($e->getMessage());
		echo json_encode($res);
	}
}

//Metodo POST para el registro a guardar en la pantalla
if (isset($_POST['modulo_Opcion'])) {
	try {
	//se invoca el metodo de guardar del controlador
	$result = $inscatalogo->guardar();
	//resultado que se envia al metodo POST
	if ($result) {
		if ($_POST["idcatalogo"] == "0") {
			$alerta = [
				"tipo" => "limpiar",
				"titulo" => "Catálogo registrado",
				"texto" => "El registro se guardo con éxito",
				"icono" => "success"
			];
		} else {
			$alerta = [
				"tipo" => "limpiar",
				"titulo" => "Catálogo actualizado",
				"texto" => "El registro se actualizo con éxito",
				"icono" => "success"
			];
		}
	} else {
		if ($_POST["idcatalogo"] == "0") {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Error",
				"texto" => "No se pudo registrar el catálogo, por favor intente nuevamente",
				"icono" => "error"
			];
		} else {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Error",
				"texto" => "No se pudo actualizar el catálogo, por favor intente nuevamente",
				"icono" => "error"
			];
		}
	}
	} catch (Exception  $e) {
		$alerta = [
			"tipo" => "simple",
			"titulo" => "Error",
			"texto" => "Error consulte con el administrador",
			"icono" => "error"
		];
		$log->guardarlog($e->getMessage());
		 
	}

	echo json_encode($alerta);
}

//Metodo POST para activar e inactivar el estado del registro
if (isset($_POST['modulo_catalogo'])) {
	try {
	$existenvalores = $inscatalogo->validarvalorescatalogos();

	if ($existenvalores > 0) {
		$alerta = [
			"tipo" => "simple",
			"titulo" => "Advertencia",
			"texto" => "No se pudo realizar la acción solicitada, el catalogo contienen valores activos. ",
			"icono" => "warning"
		];
	} else {


		//llamada al metodo del controlador 1 para activar y 0 para inactivar el estado del registro
		if ($_POST['modulo_catalogo'] == "activar") {
			$result = $inscatalogo->cambiarestado(1);
		} else {
			$result = $inscatalogo->cambiarestado(0);
		}

		//resultado que recive el metodo POST en la pantalla
		if ($result) {
			if ($_POST['modulo_catalogo'] == "activar") {
				$alerta = [
					"tipo" => "limpiar",
					"titulo" => "Activar registro",
					"texto" => "El registro se activo con éxito",
					"icono" => "success"
				];
			} else {
				$alerta = [
					"tipo" => "limpiar",
					"titulo" => "Inactivar registro",
					"texto" => "El registro se Inactivo con éxito",
					"icono" => "success"
				];
			}
		} else {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Error",
				"texto" => "No se pudo realizar la acción solicitada, por favor intente nuevamente",
				"icono" => "error"
			];
		}
	}
	} catch (Exception  $e) {
		$alerta = [
			"tipo" => "simple",
			"titulo" => "Error",
			"texto" => "Error consulte con el administrador",
			"icono" => "error"
		];
		$log->guardarlog($e->getMessage());
		
	}
	echo json_encode($alerta);
}
