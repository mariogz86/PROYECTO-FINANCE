<?php

namespace app\controllers;

use app\models\mainModel;
//delcaracion de funciones para el controlador
class opcionController extends mainModel
{
	//funcion para obtener los rols de la base de datos
	public function listar()
	{
		$consulta_datos = "select * from \"SACNSYS\".OBTENER_OPCIONMENU;";

		$datos = $this->ejecutarConsulta($consulta_datos);
		$datos = $datos->fetchAll();

		return $datos;
	}
	//Funcion para guardar los datos del rol
	public function guardar()
	{


		$nombre = trim($_POST["nombre"]);
		$descripcion = trim($_POST["descripcion"]);
		$nombrevista = trim($_POST["nombrevista"]);
		$menu = trim($_POST["cmb_menu"]);
		$orden = trim($_POST["orden"]);
		$icono = trim($_POST["icono"]);

		if ($_POST["idopcion"] == "0") {
			$sentencia = "select \"SACNSYS\".INSERTAR_OPCION('" . $nombre . "','" . $nombrevista . "','" . $descripcion . "','" . $menu . "','" . $icono . "','" . $orden . "','" . $_SESSION['id'] . "');";
			$sql = $this->actualizarDatos($sentencia);
			$sql->execute();
		} else {
			$sentencia = "select \"SACNSYS\".ACTUALIZAR_OPCION('" . $_POST["idopcion"] . "','" . $nombre . "','" . $nombrevista . "','" . $descripcion . "','" . $menu . "','" . $icono . "','" . $orden . "','" . $_SESSION['id'] . "');";
			$sql = $this->actualizarDatos($sentencia);
			$sql->execute();
		}

		return $sql;
	}

	//Funcion para validar que no exista el nombre del menu
	public function Buscaropcion()
	{

		$nombre = trim($_POST["nombre"]);

		if ($_POST["idopcion"] == "0") {
			$sentencia = "select * from \"SACNSYS\".OBTENER_OPCIONMENU where UPPER(nombre)=UPPER('" . $nombre . "');  ";
			$datos = $this->ejecutarConsulta($sentencia);
			$datos = $datos->fetchAll();
		} else {
			$sentencia = "select * from \"SACNSYS\".OBTENER_OPCIONMENU where UPPER(nombre)=UPPER('" . $nombre . "') and id_opcion not in ('" . $_POST["idopcion"] . "');  ";
			$datos = $this->ejecutarConsulta($sentencia);
			$datos = $datos->fetchAll();
		}

		return $datos;
	}


	//Funcion para cambiar de estado al rol 
	public function cambiarestado($estado)
	{

		$idcat = $_POST["id_opcion"];
		$sentencia = "select \"SACNSYS\".CAMBIARESTADO_OPCION('" . $idcat . "','" . $estado . "','" . $_SESSION['id'] . "');";
		$sql = $this->actualizarDatos($sentencia);
		$sql->execute();

		return $sql;
	}

	public function validarrolactivo()
	{
		$id = $_POST["id_opcion"];
		$consulta_datos = "select count(*) from \"SACNSYS\".roles r
				inner join \"SACNSYS\".rol_opcion ro on ro.id_rol = r.id_rol
				where ro.id_opcion = '" . $id . "' and r.u_estado=1";

		$datos = $this->ContarRegistros($consulta_datos);

		return $datos;
	}
}
