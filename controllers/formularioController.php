<?php

	namespace app\controllers;
	use app\models\mainModel;

	//delcaracion de funciones del controlador
	class formularioController extends mainModel{
		//funcion para cargar los formularios 
        public function listarformulario(){
			
			$consulta_datos="select * from \"SYSTEM\".obtener_formulario;"; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}
		//funcion para buscar si ya existe un formulario
		//parametros que se resiven por metodo POST 
		//año, tipocarga, actividad, detalle actividad, tipo  de encuesta y nombre del formulario
		public function buscarformulario(){
			$cmb_anio = $_POST["cmb_anio"];  
			$cmb_tipocarga = $_POST["cmb_tipocarga"];
			$cmb_catatividad = $_POST["cmb_catatividad"];
			$cmb_detactiv = $_POST["cmb_detactiv"];
			$cmb_tipoencuesta = $_POST["cmb_tipoencuesta"];			
			$nombre = $this->limpiarCadena($_POST["nombre"]); 

			
			if($cmb_detactiv ==""){
				$cmb_detactiv =0;
			} 


			$consulta_datos="select * from \"SYSTEM\".buscarformulario('".$cmb_detactiv."','".$nombre."','".$cmb_tipoencuesta."','".$cmb_anio."' ,'".$cmb_tipocarga."','".$cmb_catatividad."','".$_POST["idformulario"]."');"; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}
		//funcion para cargar las valores asociados a la actividad
		public function listardetalleactividad($codicatalogo){
			$consulta_datos="select * from \"SYSTEM\".obtener_valor_porcatalogo('".$codicatalogo."' );"; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}
		//Funcion para guardar los datos del formulario
		public function guardar(){
			 
			
			$hoy = date("Y-m-d");
			$cmb_anio = $_POST["cmb_anio"];
			$cmb_tipocarga = $_POST["cmb_tipocarga"];
			$cmb_catatividad = $_POST["cmb_catatividad"];
			$cmb_detactiv = $_POST["cmb_detactiv"];
			$cmb_tipoencuesta = $_POST["cmb_tipoencuesta"];
			

			$nombre = $this->limpiarCadena($_POST["nombre"]);
			$ruta = trim($_POST["ruta"]);

			if($cmb_detactiv ==""){
				$cmb_detactiv =0;
			} 


			if ($_POST["idformulario"]=="0"){
				$sentencia ="select \"SYSTEM\".insertar_formulario('".$cmb_anio."','".$cmb_tipoencuesta."','".$cmb_tipocarga."','".$cmb_catatividad."','".$cmb_detactiv."','".$nombre."','".$ruta."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
 				$sql->execute();
			  
					
			}else{
				$sentencia ="select \"SYSTEM\".actualizar_formulario('".$_POST["idformulario"]."','".$cmb_tipoencuesta."','".$cmb_anio."','".$cmb_tipocarga."','".$cmb_catatividad."','".$cmb_detactiv."','".$nombre."','".$ruta."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute();	

			} 

		 return $sql;
		}
		//funcion para cambiar ruta a los formularios seleccionados en la pantalla
		public function guardar_cambioruta(){
			  
			$idseleccionados = $_POST["hdf_seleccionados"];			 
			$ruta = $_POST["nuevaruta"]; 
			$sentencia ="select \"SYSTEM\".actualizar_ruta('".$idseleccionados."','".$ruta."','".$_SESSION['id']."');";
			$sql=$this->actualizarDatos($sentencia);
 			$sql->execute();	  
		 return $sql;
		}
	 

    }