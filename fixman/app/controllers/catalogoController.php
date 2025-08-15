<?php

	namespace app\controllers;
	use app\models\mainModel;
	//delcaracion de funciones para el controlador
	class catalogoController extends mainModel{
		//funcion para obtener los catalogos de la base de datos
        public function listarcatalogo(){
			$consulta_datos="select * from \"SYSTEM\".obtener_catalogo;"; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}
		//Funcion para guardar los datos del catalogo
		public function guardar(){
			 
			
			$hoy = date("Y-m-d");
			$nombre = $this->limpiarCadena($_POST["nombre"]);			
			$descripcion =  $_POST["descripcion"];

			if ($_POST["idcatalogo"]=="0"){
				$codigo = $_POST["codigo"];
				$sentencia ="select \"SYSTEM\".insertar_catalogo('".$nombre."','".$codigo."','".$descripcion."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute();
			}else{				
				$sentencia ="select \"SYSTEM\".actualizar_catalogo('".$_POST["idcatalogo"]."','".$nombre."','".$descripcion."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute();	

			} 

		 return $sql;
		}
		//Funcion para cambiar de estado al catalogo 
		public function cambiarestado($estado){ 

			$idcat = $_POST["id_catalogo"];
				$sentencia ="select \"SYSTEM\".cambiarestado_catalogo('".$idcat."','".$estado."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute(); 

		 return $sql;
		}

    }