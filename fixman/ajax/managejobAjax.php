<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
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


if (isset($_GET['cargadatoscita'])) {
	//metodo del controlador
	$result = $insformulario->obtenerdatoscita();
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

if (isset($_GET['cargadatospago'])) {
	//metodo del controlador
	$result = $insformulario->obtenerdatospago();
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

if (isset($_GET['obtenermovimientosjob'])) {
	//metodo del controlador
	$result = $insformulario->obtenermovimientosjob();
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
if (isset($_POST['modulo_Opcion_pago'])) {
	//se invoca el metodo de guardar del controlador
	$result = $insformulario->guardarpago();
	//resultado que se envia al metodo POST
	if ($result > 0) {
		if ($_POST["id_pago"] == "0") {
			$alerta = [
				"classform" => ".FormularioAjax4",
				"tipo" => "limpiar",
				"titulo" => "Payment",
				"texto" => "The record was saved successfully",
				"icono" => "success",
				"idgenerado" => $result
			];
		} else {
			$alerta = [
				"classform" => ".FormularioAjax4",
				"tipo" => "limpiar",
				"titulo" => "Payment",
				"texto" => "The registry was updated successfully",
				"icono" => "success",
				"idgenerado" => $result
			];
		}
	} else {
		if ($_POST["id_servicio"] == "0") {
			$alerta = [
				"classform" => ".FormularioAjax3",
				"tipo" => "simple",
				"titulo" => "Error",
				"texto" => "The record could not be saved, please try again",
				"icono" => "error"
			];
		} else {
			$alerta = [
				"classform" => ".FormularioAjax3",
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
if (isset($_POST['modulo_Opcion_cita'])) {
	//se invoca el metodo de guardar del controlador
	$result = $insformulario->guardarcita();
	//resultado que se envia al metodo POST
	if ($result > 0) {
		if ($_POST["id_cita"] == "0") {
			$alerta = [
				"classform" => ".FormularioAjax3",
				"tipo" => "limpiar",
				"titulo" => "Schedule",
				"texto" => "The record was saved successfully",
				"icono" => "success",
				"idgenerado" => $result
			];
		} else {
			$alerta = [
				"classform" => ".FormularioAjax3",
				"tipo" => "limpiar",
				"titulo" => "Schedule",
				"texto" => "The registry was updated successfully",
				"icono" => "success",
				"idgenerado" => $result
			];
		}
	} else {
		if ($_POST["id_servicio"] == "0") {
			$alerta = [
				"classform" => ".FormularioAjax3",
				"tipo" => "simple",
				"titulo" => "Error",
				"texto" => "The record could not be saved, please try again",
				"icono" => "error"
			];
		} else {
			$alerta = [
				"classform" => ".FormularioAjax3",
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


	if ($_POST['modulo_job'] == "activar") {

		$result = $insformulario->cambiarestadovalorjob('Accepted');
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
