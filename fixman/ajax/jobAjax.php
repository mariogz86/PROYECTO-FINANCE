<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
require_once "../autoload.php";

//inicializar controlador
use app\controllers\jobController;

$insformulario = new jobController();


//Metodo GET para la carga del grid en la pantalla
if (isset($_GET['cargagrid'])) {
	//metodo del controlador
	$result = $insformulario->listar();
	//resultado que se envita al Metodo GET invocado
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
}

//Metodo GET para la carga del grid en la pantalla
if (isset($_GET['cargagridservicio'])) {
	//metodo del controlador
	$result = $insformulario->listarservicios();
	//resultado que se envita al Metodo GET invocado
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
}

//Metodo POST para el registro a guardar en la pantalla
if (isset($_POST['modulo_Opcion_service'])) {
	//se invoca el metodo de guardar del controlador
	$result = $insformulario->guardarservicio();
	//resultado que se envia al metodo POST
	if ($result > 0) {
		if ($_POST["id_servicio"] == "0") {
			$alerta = [
				"classform" => ".FormularioAjax2",
				"tipo" => "limpiar",
				"titulo" => "Service",
				"texto" => "The record was saved successfully",
				"icono" => "success"
			];
		} else {
			$alerta = [
				"classform" => ".FormularioAjax2",
				"tipo" => "limpiar",
				"titulo" => "Service",
				"texto" => "The registry was updated successfully",
				"icono" => "success"
			];
		}
	} else {
		if ($_POST["id_servicio"] == "0") {
			$alerta = [
				"classform" => ".FormularioAjax2",
				"tipo" => "simple",
				"titulo" => "Error",
				"texto" => "The record could not be saved, please try again",
				"icono" => "error"
			];
		} else {
			$alerta = [
				"classform" => ".FormularioAjax2",
				"tipo" => "simple",
				"titulo" => "Error",
				"texto" => "The record could not be updated, please try again",
				"icono" => "error"
			];
		}
	}
	echo json_encode($alerta);
}

//Metodo POST para el registro a guardar en la pantalla
if (isset($_POST['modulo_Opcion'])) {
	//se invoca el metodo de guardar del controlador
	$result = $insformulario->guardar();
	//resultado que se envia al metodo POST
	if ($result > 0) {
		if ($_POST["idjob"] == "0") {
			$alerta = [
				"classform" => ".FormularioAjax",
				"tipo" => "limpiar",
				"titulo" => "Company",
				"texto" => "The record was saved successfully",
				"icono" => "success"
			];
		} else {
			$alerta = [
				"classform" => ".FormularioAjax",
				"tipo" => "limpiar",
				"titulo" => "Company",
				"texto" => "The registry was updated successfully",
				"icono" => "success"
			];
		}
	} else {
		if ($_POST["idjob"] == "0") {
			$alerta = [
				"classform" => ".FormularioAjax",
				"tipo" => "simple",
				"titulo" => "Error",
				"texto" => "The record could not be saved, please try again",
				"icono" => "error"
			];
		} else {
			$alerta = [
				"classform" => ".FormularioAjax",
				"tipo" => "simple",
				"titulo" => "Error",
				"texto" => "The record could not be updated, please try again",
				"icono" => "error"
			];
		}
	}
	echo json_encode($alerta);
}





//Metodo POST para el guardado de un registro

if (isset($_POST['modulo_job'])) {
	if ($_POST['modulo_job'] == "inactivar") {

		$result = $insformulario->cambiarestado(0);
		$alerta = [
			"classform" => ".FormularioAjax",
			"tipo" => "limpiar",
			"titulo" => "Deactivate registration",
			"texto" => "The registration was successfully deactivated",
			"icono" => "success"
		];
	}


	if ($_POST['modulo_job'] == "activar") {

		$result = $insformulario->cambiarestado(1);
		$alerta = [
			"classform" => ".FormularioAjax",
			"tipo" => "limpiar",
			"titulo" => "Activate registration",
			"texto" => "Registration was successfully activated",
			"icono" => "success"
		];
	}
	echo json_encode($alerta);
}



if (isset($_POST['modulo_jobservice'])) {
	if ($_POST['modulo_jobservice'] == "eliminar") {

		$result = $insformulario->cambiarestadoservicio(0);
		$alerta = [
			"classform" => ".FormularioAcciones",
			"tipo" => "limpiar",
			"titulo" => "Deactivate registration",
			"texto" => "The record was deleted successfully",
			"icono" => "success"
		];
	}
 
	echo json_encode($alerta);
}
