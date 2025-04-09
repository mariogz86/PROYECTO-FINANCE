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
class usuarioController extends mainModel
{
	//funcion para cargar los formularios 
	public function listarformulario()
	{

		$consulta_datos = "select * from \"SYSTEM\".OBTENER_USUARIOS;";

		$datos = $this->ejecutarConsulta($consulta_datos);
		$datos = $datos->fetchAll();

		return $datos;
	}

	//funcion para cargar los formularios 
	public function Buscarusuario()
	{
		if ($_POST["idusuario"] == "0") {
			$consulta_datos = "select count(*) from \"SYSTEM\".OBTENER_USUARIOS where usuario='" . $_POST["usuario_usuario"] . "';";
		} else {
			$consulta_datos = "select count(*) from \"SYSTEM\".OBTENER_USUARIOS where usuario='" . $_POST["usuario_usuario"] . "' and id_usuario not in ('" . $_POST["idusuario"] . "');";
		}


		$datos = $this->ContarRegistros($consulta_datos);

		return $datos;
	}

	//Funcion para guardar los datos del formulario
	public function guardar()
	{


		$usuario_nombre = $this->limpiarCadena($_POST["usuario_nombre"]);
		$usuario_apellido = $this->limpiarCadena($_POST["usuario_apellido"]);
		$usuario_usuario = $this->limpiarCadena($_POST["usuario_usuario"]);
		$usuario_email = $this->limpiarCadena($_POST["usuario_email"]);
		$clave = $this->generatePassword(8);
		$cmb_rol = $_POST["cmb_rol"];

		$clavenueva = password_hash($clave, PASSWORD_BCRYPT, ["cost" => 10]);


		$hoy = date("Y-m-d");
		$date_now = date('Y-m-d');
		$fechavencimiento = strtotime('+90 day', strtotime($date_now));
		$fechavencimiento = date('Y-m-d', $fechavencimiento);






		if ($_POST["idusuario"] == "0") {
			$sentencia = "select \"SYSTEM\".INSERTAR_USUARIO('" . $usuario_nombre . "','" . $usuario_apellido . "','" . $usuario_usuario . "','" . $usuario_email . "','" . $clavenueva . "','" . $cmb_rol . "','" . $fechavencimiento . "','" . $_SESSION['id'] . "');";
			$sql = $this->actualizarDatos($sentencia);
			$sql->execute();
			$total = (int) $sql->fetchColumn();

			$this->usuariocreado($usuario_nombre, $usuario_apellido, $usuario_usuario, $clave,$usuario_email);
		} else {
			$sentencia = "select \"SYSTEM\".ACTUALIZAR_USUARIO('" . $_POST["idusuario"] . "','" . $usuario_nombre . "','" . $usuario_apellido . "','" . $usuario_usuario . "','" . $usuario_email . "','" . $clave . "','" . $cmb_rol . "','" . $fechavencimiento . "','" . $_SESSION['id'] . "');";
			$sql = $this->actualizarDatos($sentencia);
			$sql->execute();
			$total = (int) $sql->fetchColumn();
		}

		return $total;
	}

	//Funcion para cambiar de estado al catalogo 
	public function bloquear($estado)
	{

		$idcat = $_POST["id_usuario"];
		$sentencia = "select \"SYSTEM\".BLOQUEAR_USUARIO('" . $idcat . "','" . $estado . "','" . $_SESSION['id'] . "');";
		$sql = $this->actualizarDatos($sentencia);
		$sql->execute();

		return $sql;
	}

	//Funcion para cambiar de estado al catalogo 
	public function cambiarestado($estado)
	{

		$idcat = $_POST["id_usuario"];
		$sentencia = "select \"SYSTEM\".cambiarestado_usuario('" . $idcat . "','" . $estado . "','" . $_SESSION['id'] . "');";
		$sql = $this->actualizarDatos($sentencia);
		$sql->execute();

		return $sql;
	}

	//funcion para generar una clave temporal para el usuario
	function generatePassword($length)
	{
		$key = "";
		$pattern = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$max = strlen($pattern) - 1;
		for ($i = 0; $i < $length; $i++) {
			$key .= substr($pattern, mt_rand(0, $max), 1);
		}
		return $key;
	}


	//funcion para enviar correo cuando la cuenta esta bloqueada por varios intentos
	public function usuariocreado($nombre, $apellido, $usuario, $clave,$usuario_email)
	{
		$phpmailer = new PHPMailer(true);
		$phpmailer->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true,
			)
		);
		$phpmailer->isSMTP();
		$phpmailer->Host       = 'smtp-relay.sendinblue.com';
		$phpmailer->SMTPAuth   = true;
		$phpmailer->Username   = '854a84002@smtp-brevo.com';  // Tu correo registrado en Sendinblue
		$phpmailer->Password   = '7DJzMZct3NVwC8X1';  // API Key proporcionada por Sendinblue
		$phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		$phpmailer->Port       = 587;
		$phpmailer->SMTPDebug = APP_SMTPDebug;
 
		$phpmailer->setFrom(APP_Username);
		$phpmailer->addAddress($usuario_email);
		$phpmailer->isHTML(true); // Set email format to plain text

		$phpmailer->Subject = "Cuenta de usuario";
		$phpmailer->Body    = "Estimado(a) <b>" . $nombre . " " . $apellido . "</b>:<br><br>"
			. "Le informamos que su cuenta de usuario <b>" . $usuario . "</b> del sistema <b> FIXMAN </b> ha sido creada, "
			. "su clave temporal para ingresar al sistema es la siguiente <b>" . $clave . "</b> se recomienda cambiar la clave	.<br><br>";

		if (!$phpmailer->send()) {
			echo  'Error en el envío de correo electrónico, Error: ' . $phpmailer->ErrorInfo .
				' Para mayor información pongase en contacto con el administrador del sistema';
		}
	}
}
