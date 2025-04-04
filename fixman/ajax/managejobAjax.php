<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
require_once('../tcpdf/examples/tcpdf_include.php');
require_once "../autoload.php";

//inicializar controlador
use app\controllers\managejobController;

$insformulario = new managejobController();


//Metodo GET para la carga del grid en la pantalla
if (isset($_GET['cargagrid'])) {
	//metodo del controlador
	$result = $insformulario->listar();
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
if (isset($_GET['cargarpartes'])) {
	//metodo del controlador
	$result = $insformulario->listarcargapartes();
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
if (isset($_GET['cargadiagnostico'])) {
	//metodo del controlador
	$result = $insformulario->listarcargadiagnostico();
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



if (isset($_GET['cargagridservicio'])) {
	//metodo del controlador
	$result = $insformulario->listarservicios();
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


if (isset($_GET['cargadatoscita'])) {
	//metodo del controlador
	$result = $insformulario->obtenerdatoscita();
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

if (isset($_GET['cargadatospago'])) {
	//metodo del controlador
	$result = $insformulario->obtenerdatospago();
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

if (isset($_GET['obtenermovimientosjob'])) {
	//metodo del controlador
	$result = $insformulario->obtenermovimientosjob();
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











//Metodo POST para el registro a guardar en la pantalla
if (isset($_POST['modulo_movimiento'])) {
	//se invoca el metodo de guardar del controlador
	$result = $insformulario->guardarmovimientojob();
	//resultado que se envia al metodo POST
	if ($result > 0) {
		$alerta = [
			"classform" => ".FormularioAjax5",
			"tipo" => "limpiar",
			"titulo" => "Job State",
			"texto" => "The record was saved successfully",
			"icono" => "success"
		];
	}
	echo json_encode($alerta);
}




//Metodo POST para el registro a guardar en la pantalla
if (isset($_POST['modulo_diagnostico'])) {
	//se invoca el metodo de guardar del controlador
	$result = $insformulario->guardardiagnostico();
	//resultado que se envia al metodo POST
	if ($result > 0) {
		$alerta = [
			"classform" => ".FormularioAjax6",
			"tipo" => "limpiar",
			"titulo" => "Diagnosis",
			"texto" => "The record was saved successfully",
			"icono" => "success",
			"id_diagnostico" => $result
		];
	}
	echo json_encode($alerta);
}


//Metodo POST para el registro a guardar en la pantalla
if (isset($_POST['modulo_parte'])) {
	//se invoca el metodo de guardar del controlador
	$result = $insformulario->guardarparte();
	//resultado que se envia al metodo POST
	if ($result > 0) {
		$alerta = [
			"classform" => ".FormularioAjax7",
			"tipo" => "limpiar",
			"titulo" => "Part service",
			"texto" => "The record was saved successfully",
			"icono" => "success"
		];
	}
	echo json_encode($alerta);
}







//Metodo POST para el guardado de un registro

if (isset($_POST['modulo_job'])) {
	if ($_POST['modulo_job'] == "aceptar") {

		$result = $insformulario->cambiarestadovalorjob('Accepted');
		$alerta = [
			"classform" => ".FormularioAjax",
			"tipo" => "limpiar",
			"titulo" => "Accept Job",
			"texto" => "The registration was successfully accept",
			"icono" => "success"
		];
	}
	echo json_encode($alerta);
}


if (isset($_POST['modulo_serviceparte'])) {
	if ($_POST['modulo_serviceparte'] == "eliminar") {

		$result = $insformulario->eliminarparte();
		$alerta = [
			"classform" => ".FormularioAccionespartes",
			"tipo" => "limpiar",
			"titulo" => "Part",
			"texto" => "The record was deleted successfully",
			"icono" => "success"
		];
	}
	echo json_encode($alerta);
}



