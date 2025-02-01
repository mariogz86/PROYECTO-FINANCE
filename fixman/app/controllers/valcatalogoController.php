<?php

	namespace app\controllers;
	use app\models\mainModel;
	//declaracion de funciones
	class valcatalogoController extends mainModel{
		//funcino para cargar los catalogos del sistema
        public function listarvalcatalogo(){
			$consulta_datos="select * from \"SYSTEM\".obtener_valcatalogo;"; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}
		//Funcion para guardar los datos del catalogo
		public function guardar(){
			 
			
			$hoy = date("Y-m-d");
			$idcat = $_POST["cmb_catalogo"];
			$nombre = $_POST["nombre"];
			$descripcion = $_POST["descripcion"];

			if ($_POST["idcatalogovalor"]=="0"){
				$sentencia ="select \"SYSTEM\".insertar_valorcat('".$idcat."','".$nombre."','".$descripcion."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute();
			}else{
				$sentencia ="select \"SYSTEM\".actualizar_valorcat('".$_POST["idcatalogovalor"]."','".$idcat."','".$nombre."','".$descripcion."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute();	

			} 

		 return $sql;
		}
		//Funcion para cambiar de estado al catalogo 
		public function cambiarestado($estado){ 

			$idcat = $_POST["id_catalogovalor"];
				$sentencia ="select \"SYSTEM\".cambiarestado_valcatalogo('".$idcat."','".$estado."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute(); 

		 return $sql;
		}

    }