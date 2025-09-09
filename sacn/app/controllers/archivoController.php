<?php

	namespace app\controllers;
	use app\models\mainModel;
//declaracion de metodos del controlador
	class archivoController extends mainModel{
		//Funcion para cargar la lista de archivos guardadas en la base de datos
        public function listararchivo(){
			
			$consulta_datos="select * from \"SACNSYS\".obtener_archivofuente;"; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}
		//funcion para buscar los archivos asociados a un formulario
        public function buscararachivo_porformulario($id){
			
			$consulta_datos="SELECT * FROM \"SACNSYS\".obtener_archivofuente where id_formulario=".$id.";"; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}
		//funcion para guardar los registros seleccionados en la pantalla
		//se utiliza guardado por via JSON
		public function guardar($json){
			
			$consulta_datos="select \"SACNSYS\".guardararchivosfuente(".$_POST["formulario"].",'".$json."',".$_SESSION['id'].");"; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}

		//funcion para cambiar ruta a los archivos seleccionados en la pantalla
		public function guardar_cambioruta(){
			  
			$idseleccionados = $_POST["hdf_seleccionados"];			 
			$ruta = TRIM($_POST["nuevaruta"]); 
			$sentencia ="select \"SACNSYS\".cambiar_ruta('".$idseleccionados."','".$ruta."','".$_SESSION['id']."');";
			$sql=$this->actualizarDatos($sentencia);
 			$sql->execute();	  
		 return $sql;
		}

		//Funcion para cambiar de estado al archivo
		public function cambiarestado($estado){ 

			$idcat = $_POST["id_archivofuente"];
				$sentencia ="select \"SACNSYS\".cambiarestado_archivo('".$idcat."','".$estado."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute(); 

		 return $sql;
		}
    
    }