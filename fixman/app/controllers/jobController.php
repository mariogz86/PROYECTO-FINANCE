<?php

namespace app\controllers;

use app\models\mainModel;

if (isset($_POST['modulo_Opcion'])) {
	require_once '../phpmailer/src/PHPMailer.php';
	require_once '../phpmailer/src/SMTP.php';
	require_once '../phpmailer/src/Exception.php';
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//delcaracion de funciones del controlador
class jobController extends mainModel
{
	//funcion para cargar los formularios 
	public function listar()
	{

		$consulta_datos = "select * from \"SYSTEM\".OBTENER_JOBS;";

		$datos = $this->ejecutarConsulta($consulta_datos);
		$datos = $datos->fetchAll();

		return $datos;
	}

		//funcion para cargar los formularios 
		public function listardashboard()
		{
	
			if ($_SESSION['rol'] == "Administrator") {
			$consulta_datos = "select * from \"SYSTEM\".dashboardjobs";

		}
			else {
				$consulta_datos = "SELECT * FROM \"SYSTEM\".verdashboard(" . $_SESSION['id'] . ");";
			}
	
	
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
	
			return $datos;
		}

	public function listarservicios()
	{

		$consulta_datos = "select * from \"SYSTEM\".OBTENER_SERVICE where id_trabajo =" . $_GET['cargagridservicio'] . ";";

		$datos = $this->ejecutarConsulta($consulta_datos);
		$datos = $datos->fetchAll();

		return $datos;
	}

	public function obtenerdatoscita()
	{

		$consulta_datos = "select * from \"SYSTEM\".CITA where id_trabajo =" . $_GET['cargadatoscita'] . ";";

		$datos = $this->ejecutarConsulta($consulta_datos);
		$datos = $datos->fetchAll();

		return $datos;
	}

	public function obtenerdatospago()
	{

		$consulta_datos = "select * from \"SYSTEM\".PAYMENT where id_trabajo =" . $_GET['cargadatospago'] . ";";

		$datos = $this->ejecutarConsulta($consulta_datos);
		$datos = $datos->fetchAll();

		return $datos;
	}

	//funcion para cargar los formularios 
	public function Buscarusuario()
	{
		if ($_POST["idjob"] == "0") {
			$consulta_datos = "select count(*) from \"SYSTEM\".OBTENER_COMPANY where UPPER(nombre)=UPPER('" . $_POST["nombre"] . "');";
		} else {
			$consulta_datos = "select count(*) from \"SYSTEM\".OBTENER_COMPANY where UPPER(nombre)=UPPER('" . $_POST["nombre"] . "') and id_company not in ('" . $_POST["idCompany"] . "');";
		}


		$datos = $this->ContarRegistros($consulta_datos);

		return $datos;
	}

	//Funcion para guardar los datos del formulario
	public function guardar()
	{
			$datosform[] = array(
				'idjob' => $_POST["idjob"],
				'idCompany' => $_POST["cmb_company"],
				'fullname' => $this->limpiarCadena($_POST["fullname"]),
				'city' => $this->limpiarCadena($_POST["city"]),
				'codigozip' => $_POST["codigozip"],
				'direccion' => $this->limpiarCadena($_POST["direccion"]),
				'cmb_estado' => $_POST["cmb_estado"],
				'phone' => $this->limpiarCadena($_POST["phone"]),
				'telefono' => $this->limpiarCadena($_POST["telefono"]),
				'email' => $this->limpiarCadena($_POST["email"]),
				'companyname' => $this->limpiarCadena($_POST["companyname"]),
				'contactinfo' => $this->limpiarCadena($_POST["contactinfo"]),
				'contactphone' => $this->limpiarCadena($_POST["contactphone"]),
				'contactmail' => $this->limpiarCadena($_POST["contactmail"]),
				'nte' => $_POST["nte"],
				'fee' => $_POST["fee"],
				'cmb_tecnico' =>  $_POST["cmb_tecnico"]
			);
		 

		$datos = json_encode($datosform);


		if ($_POST["idjob"] == "0") {
			$sentencia = "select \"SYSTEM\".INSERTAR_JOB('" . $datos . "','" . $_SESSION['id'] . "');";
			$sql = $this->actualizarDatos($sentencia);
			$sql->execute();
			$total = (int) $sql->fetchColumn();
		} else {
			$sentencia = "select \"SYSTEM\".ACTUALIZAR_JOB('" . $datos . "','" . $_SESSION['id'] . "');";
			$sql = $this->actualizarDatos($sentencia);
			$sql->execute();
			$total = (int) $sql->fetchColumn();
		}

		return $total;
	}

	//Funcion para guardar los datos del formulario
	public function guardarservicio()
	{

		$datosform[] = array(
			'id_servicio' => $_POST["id_servicio"],
			'id_trabajo' => $_POST["idjob_service"],
			'id_valservice' => $_POST["cmb_service"],
			'id_valappliance' => $_POST["cmb_appliance"],
			'id_valbrand' => $_POST["cmb_brand"],
			'id_valsymptom' => $_POST["cmb_symptom"],
			'model' => $this->limpiarCadena($_POST["model"]),
			'problemdetail' => $this->limpiarCadena($_POST["problemdetail"]),
			'servicefee' => $_POST["servicefee"],
			'covered' => $_POST["covered"],
		);

		$datos = json_encode($datosform);


		if ($_POST["id_servicio"] == "0") {
			$sentencia = "select \"SYSTEM\".INSERTAR_SERVICIO('" . $datos . "','" . $_SESSION['id'] . "');";
			$sql = $this->actualizarDatos($sentencia);
			$sql->execute();
			$total = (int) $sql->fetchColumn();
		} else {
			$sentencia = "select \"SYSTEM\".ACTUALIZAR_SERVICE('" . $datos . "','" . $_SESSION['id'] . "');";
			$sql = $this->actualizarDatos($sentencia);
			$sql->execute();
			$total = (int) $sql->fetchColumn();
		}

		return $total;
	}

	//Funcion para guardar los datos del formulario
	public function guardarcita()
	{

		$datosform[] = array(
			'id_cita' => $_POST["id_cita"],
			'id_trabajo' => $_POST["idjob_cita"],
			'fechacita' => $_POST["fechacita"],
			'horaini' => $_POST["horaini"],
			'minini' => $_POST["minini"],
			'tiempoini' => $_POST["tiempoini"], 
			'horafin' => $_POST["horafin"],
			'minfin' => $_POST["minfin"],
			'tiempofin' => $_POST["tiempofin"], 
			'problemdetail' => $this->limpiarCadena($_POST["nota"]), 
		);

		$datos = json_encode($datosform);


		if ($_POST["id_cita"] == "0") {
			$sentencia = "select \"SYSTEM\".INSERTAR_CITA('" . $datos . "','" . $_SESSION['id'] . "');";
			$sql = $this->actualizarDatos($sentencia);
			$sql->execute();
			$total = (int) $sql->fetchColumn();
		} else {
			$sentencia = "select \"SYSTEM\".ACTUALIZAR_CITA('" . $datos . "','" . $_SESSION['id'] . "');";
			$sql = $this->actualizarDatos($sentencia);
			$sql->execute();
			$total = (int) $sql->fetchColumn();
		}

		return $total;
	}

		//Funcion para guardar los datos del formulario
		public function guardarpago()
		{
	
			$datosform[] = array(
				'id_pago' => $_POST["id_pago"],
				'id_trabajo' => $_POST["idjob_pago"],
				'idvalpago' => $_POST["cmb_pago"], 
				'nota' => $this->limpiarCadena($_POST["notapayment"]), 
			);
	
			$datos = json_encode($datosform);
	
	
			if ($_POST["id_pago"] == "0") {
				$sentencia = "select \"SYSTEM\".INSERTAR_PAGO('" . $datos . "','" . $_SESSION['id'] . "');";
				$sql = $this->actualizarDatos($sentencia);
				$sql->execute();
				$total = (int) $sql->fetchColumn();
			} else {
				$sentencia = "select \"SYSTEM\".ACTUALIZAR_PAGO('" . $datos . "','" . $_SESSION['id'] . "');";
				$sql = $this->actualizarDatos($sentencia);
				$sql->execute();
				$total = (int) $sql->fetchColumn();
			}
	
			return $total;
		}


	//Funcion para cambiar de estado al catalogo 
	public function cambiarestado($estado)
	{

		$idcat = $_POST["id_trabajo"];
		$sentencia = "select \"SYSTEM\".cambiarestado_job('" . $idcat . "','" . $estado . "','" . $_SESSION['id'] . "');";
		$sql = $this->actualizarDatos($sentencia);
		$sql->execute();

		return $sql;
	}


	//Funcion para cambiar de estado al catalogo 
	public function cambiarestadoservicio($estado)
	{

		$idcat = $_POST["id_servicio"];
		$sentencia = "select \"SYSTEM\".cambiarestado_service('" . $idcat . "','" . $estado . "','" . $_SESSION['id'] . "');";
		$sql = $this->actualizarDatos($sentencia);
		$sql->execute();

		return $sql;
	}


	//Funcion para cambiar de estado al catalogo 
	public function subirimagen($tarea_id, $nombreImagen, $rutaImagen)
	{ 
		$sentencia = "select \"SYSTEM\".INSERTAR_IMAGEN('" . $tarea_id . "','" . $nombreImagen . "','" .$rutaImagen . "');";
		$sql = $this->actualizarDatos($sentencia);
		$sql->execute();
		$total = (int) $sql->fetchColumn();

		return $total;
	}

	//Funcion para cambiar de estado al catalogo 
	public function listarimagenes()
	{

		$id = $_GET['cargarimagenes'];
		$consulta_datos = "select * from \"SYSTEM\".JOBIMAGENES where trabajo_id=" . $id . ";";
		$datos = $this->ejecutarConsulta($consulta_datos);
		$datos = $datos->fetchAll();

		return $datos;
	}


	public function eliminarimagen() {
		$consulta_datos = "SELECT ruta FROM \"SYSTEM\".JOBIMAGENES WHERE id_imagen =" . $_GET['eliminar'] . ";";
		$datos = $this->ejecutarConsulta($consulta_datos);
		$datos = $datos->fetchAll();
	
		if ($datos) {
			unlink($datos[0]['ruta']); // Eliminar archivo físico
			$sentencia = " delete FROM \"SYSTEM\".JOBIMAGENES WHERE id_imagen =" . $_GET['eliminar'] . "; ";
			$sql = $this->actualizarDatos($sentencia);
			$sql->execute();
		}
		return $sql;
	}
}
