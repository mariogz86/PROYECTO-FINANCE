<?php

	namespace app\controllers;
	use app\models\mainModel;
	//declaracion de funciones para el controlador
	class hojaController extends mainModel{
		//funcion para cargar las hojas guardadas en la base de datos
        public function listarhoja(){	
			
			$consulta_datos="select * from \"SACNSYS\".obtener_hoja;"; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}
		//funcion para obtener el formulario y buscar las hojas asociadas
		public function obtenerformulario($id){
			
			$consulta_datos="select * from \"SACNSYS\".obtener_formulario  where id_formulario='".$id."';"; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}

		//funcion para buscar si la hoja asociada al formulario ya existe en la base de datos
		public function buscarhoja(){
			$cmb_formulario = $_POST["cmb_formulario"];
			$cmb_hoja = $_POST["cmb_hoja"];    

			$consulta_datos="select * from \"SACNSYS\".buscarhoja('".$cmb_formulario."','".$cmb_hoja."','".$_POST["idhoja"]."');"; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		} 
		 
		//Funcion para guardar los datos del hoja
		public function guardar(){
			 
			
			$hoy = date("Y-m-d");
			$cmb_formulario = $_POST["cmb_formulario"];
			$cmb_hoja = $_POST["cmb_hoja"];   

			 

			if ($_POST["idhoja"]=="0"){
				$sentencia ="select \"SACNSYS\".insertar_hoja('".$cmb_formulario."','".$cmb_hoja."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
 				$sql->execute();
			  
					
			}else{
				$sentencia ="select \"SACNSYS\".actualizar_hoja('".$_POST["idhoja"]."','".$cmb_formulario."','".$cmb_hoja."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute();	

			} 

		 return $sql;
		}

		//Funcion para cambiar de estado al catalogo 
		public function cambiarestado($estado){ 

			$idhoja = $_POST["id_hoja"];
				$sentencia ="select \"SACNSYS\".cambiarestado_hoja('".$idhoja."','".$estado."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute(); 

		 return $sql;
		}

		 
	 

    }