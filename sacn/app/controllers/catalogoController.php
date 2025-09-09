<?php

	namespace app\controllers;
	use app\models\mainModel;
	//delcaracion de funciones para el controlador
	class catalogoController extends mainModel{
		//funcion para obtener los catalogos de la base de datos
        public function listarcatalogo(){
			$consulta_datos="select * from \"SACNSYS\".obtener_catalogo;"; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}

		public function validarvalorescatalogos(){
			$consulta_datos="select * from \"SACNSYS\".catalogovalor where id_catalogo='".$_POST["id_catalogo"]."' and u_estado=1;"; 

			$datos = $this->ContarRegistros($consulta_datos); 

			return $datos;
		}
		//Funcion para guardar los datos del catalogo
		public function guardar(){
			 
			
			$hoy = date("Y-m-d");
			$nombre = $_POST["nombre"];			
			$descripcion = $_POST["descripcion"];

			if ($_POST["idcatalogo"]=="0"){
				$codigo = $_POST["codigo"];
				$sentencia ="select \"SACNSYS\".insertar_catalogo('".$nombre."','".$codigo."','".$descripcion."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute();
			}else{				
				$sentencia ="select \"SACNSYS\".actualizar_catalogo('".$_POST["idcatalogo"]."','".$nombre."','".$descripcion."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute();	

			} 

		 return $sql;
		}
		//Funcion para cambiar de estado al catalogo 
		public function cambiarestado($estado){ 

			$idcat = $_POST["id_catalogo"];
				$sentencia ="select \"SACNSYS\".cambiarestado_catalogo('".$idcat."','".$estado."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute(); 

		 return $sql;
		}

    }