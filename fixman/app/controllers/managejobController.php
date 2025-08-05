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
class managejobController extends mainModel
{
	//funcion para cargar los formularios 
	public function listar()
	{
		if ($_SESSION['rol'] != "Administrator") {
			$consulta_datos = "select * from \"SYSTEM\".OBTENER_JOBS where id_tecnico='" . $_SESSION['id'] . "' and estadojob not in('complete','Completed Bookind Date')";
		} else {
			$consulta_datos = "select * from \"SYSTEM\".OBTENER_JOBS where estadojob not in('complete','Completed Bookind Date');";
		}


		$datos = $this->ejecutarConsulta($consulta_datos);
		$datos = $datos->fetchAll();

		return $datos;
	}


	//funcion para cargar los formularios 
	public function listarcargadiagnostico()
	{

		$consulta_datos = "select * from \"SYSTEM\".DIAGNOSTICO where id_servicio =" . $_GET['cargadiagnostico'] . ";";

		$datos = $this->ejecutarConsulta($consulta_datos);
		$datos = $datos->fetchAll();

		return $datos;
	}

	//funcion para cargar los formularios 
	public function listarcargapartes()
	{

		$consulta_datos = "select * from \"SYSTEM\".OBTENERPARTES where id_servicio =" . $_GET['cargarpartes'] . ";";

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

	public function listarreporteservicios()
	{

		$consulta_datos = "select * from \"SYSTEM\".OBTENER_REPORTESERVICE where id_trabajo =" . $_GET['cargagridreporteservicio'] . ";";

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

	public function obtenermovimientosjob()
	{

		$consulta_datos = "select m.*,cv.nombre as estadojob, UU.Usuario as Usuario from \"SYSTEM\".movimientotrabajo m
				INNER JOIN \"SYSTEM\".USUARIOS UU ON UU.ID_USUARIO = m.USUARIO_CREACION
				inner join \"SYSTEM\".catalogovalor cv on cv.id_catalogovalor=m.id_estadotrabajo where m.id_trabajo =" . $_GET['obtenermovimientosjob'] . " order by id_movimiento asc;";

		$datos = $this->ejecutarConsulta($consulta_datos);
		$datos = $datos->fetchAll();

		return $datos;
	}

	//Funcion para guardar los datos del formulario
	public function guardarreporteservicio($id_reporteservicio,$idjob_reporteservicio,$appliance,$brand,$model,$serial,$problem,$tipocable,$otrocable,$factor,$otrofactor,$laborcosto,$requiereparte,$reparado,$detalles_json)
	{
		if ($id_reporteservicio == "0") {
			$sentencia = "select \"SYSTEM\".GUARDARREPORTESERVICIO('" . $idjob_reporteservicio . "','" . $appliance . "','" . $brand . "','" . $model . "','" . $serial . "','" . $problem . "','" . $tipocable . "','" . $otrocable . "','" . $factor . "','" . $otrofactor . "','" . $laborcosto . "','" . $requiereparte . "','" . $reparado . "','" . $detalles_json . "','" . $_SESSION['id'] . "');";
			$sql = $this->actualizarDatos($sentencia);
			$sql->execute();
			$total = (int) $sql->fetchColumn();
		} else {
			$sentencia = "select \"SYSTEM\".ACTUALIZARREPORTESERVICIO('" . $id_reporteservicio . "','" . $idjob_reporteservicio . "','" . $appliance . "','" . $brand . "','" . $model . "','" . $serial . "','" . $problem . "','" . $tipocable . "','" . $otrocable . "','" . $factor . "','" . $otrofactor . "','" . $laborcosto . "','" . $requiereparte . "','" . $reparado . "','" . $detalles_json . "','" . $_SESSION['id'] . "');";
			$sql = $this->actualizarDatos($sentencia);
			$sql->execute();
			$total = (int) $sql->fetchColumn();
		}

		return $total;
	}


	//Funcion para guardar los datos del formulario
	public function guardardiagnostico()
	{
		if ($_POST["id_diagnostico"] == "0") {
			$sentencia = "select \"SYSTEM\".GUARDARDIAGNOSTICO('" . $_POST["id_servicio"] . "','" . $_POST["serial"] . "','" . $_POST["diagnostico"] . "','" . $_POST["laborfee"] . "','" . $_SESSION['id'] . "');";
			$sql = $this->actualizarDatos($sentencia);
			$sql->execute();
			$total = (int) $sql->fetchColumn();
		} else {
			$sentencia = "select \"SYSTEM\".ACTUALIZARDIAGNOSTICO('" . $_POST["id_diagnostico"] . "','" . $_POST["id_servicio"] . "','" . $_POST["serial"] . "','" . $_POST["diagnostico"] . "','" . $_POST["laborfee"] . "','" . $_SESSION['id'] . "');";
			$sql = $this->actualizarDatos($sentencia);
			$sql->execute();
			$total = (int) $sql->fetchColumn();
		}

		return $total;
	}

	//Funcion para guardar los datos del formulario
	public function guardarparte()
	{
		if ($_POST["id_parte"] == "0") {
			$sentencia = "select \"SYSTEM\".GUARDARPARTE('" . $_POST["id_servicio_parte"] . "','" . $_POST["cmb_part"] . "','" . $_POST["cantidad"] . "','" . $_POST["serialparte"] . "','" . $_POST["costo"] . "','" . $_SESSION['id'] . "');";
			$sql = $this->actualizarDatos($sentencia);
			$sql->execute();
			$total = (int) $sql->fetchColumn();
		} else {
			$sentencia = "select \"SYSTEM\".ACTUALIZARPARTE('" . $_POST["id_parte"] . "','" . $_POST["id_servicio_parte"] . "','" . $_POST["cmb_part"] . "','" . $_POST["cantidad"] . "','" . $_POST["serialparte"] . "','" . $_POST["costo"] . "');";
			$sql = $this->actualizarDatos($sentencia);
			$sql->execute();
			$total = (int) $sql->fetchColumn();
		}

		return $total;
	}




	//Funcion para cambiar de estado al catalogo 
	public function cambiarestadovalorjob($estado)
	{

		$idcat = $_POST["id_trabajo"];
		$sentencia = "select \"SYSTEM\".CAMBIARESTADOVALOR_JOB('" . $idcat . "','" . $estado . "','Job Accepted','" . $_SESSION['id'] . "');";
		$sql = $this->actualizarDatos($sentencia);
		$sql->execute();

		return $sql;
	}

	//Funcion para cambiar de estado al catalogo 
	public function guardarmovimientojob()
	{

		$idjob_movimiento = $_POST["idjob_movimiento"];
		$cmb_estadojob = $_POST["cmb_estadojob"];
		$notacambioestado = $_POST["notacambioestado"];
		$sentencia = "select \"SYSTEM\".guardarmovimientojob('" . $idjob_movimiento . "','" . $cmb_estadojob . "','" . $notacambioestado . "','" . $_SESSION['id'] . "');";
		$sql = $this->actualizarDatos($sentencia);
		$sql->execute();
		$total = (int) $sql->fetchColumn();

		return $total;
	}
	public function eliminarparte()
	{

		$idcat = $_POST["id_servicioparte"];
		$sentencia = "select \"SYSTEM\".ELIMINARPARTE('" . $idcat . "');";
		$sql = $this->actualizarDatos($sentencia);
		$sql->execute();

		return $sql;
	}

	public function eliminarreporteservicio()
	{

		$idcat = $_POST["id_reporteservicio"];
		$sentencia = "select \"SYSTEM\".ELIMINARREPORTESERVICIO('" . $idcat . "');";
		$sql = $this->actualizarDatos($sentencia);
		$sql->execute();

		return $sql;
	}
}
