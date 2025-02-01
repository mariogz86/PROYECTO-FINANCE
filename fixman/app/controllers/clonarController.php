<?php

	namespace app\controllers;
	use app\models\mainModel;

	//delcaracion de funciones del controlador
	class clonarController extends mainModel{
		//funcion para obtener el formulario y buscar las hojas asociadas
		public function obtenerformulario($id){
					
			$consulta_datos="select * from \"SYSTEM\".obtener_formulario  where id_formulario='".$id."';"; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}

		//funcion para buscar si ya existe un formulario
		//parametros que se resiven por metodo POST 
		//año, tipocarga, actividad, detalle actividad, tipo  de encuesta y nombre del formulario
		public function buscarformulario($cmb_anio,$cmb_tipocarga,$cmb_catatividad,$cmb_detactiv,$cmb_tipoencuesta,$nombre){ 

			if($cmb_detactiv ==null){
				$cmb_detactiv =0;
			} 

			$consulta_datos="select * from \"SYSTEM\".buscarformulario('".$cmb_detactiv."','".$nombre."','".$cmb_tipoencuesta."','".$cmb_anio."' ,'".$cmb_tipocarga."','".$cmb_catatividad."','0');"; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}

		public function guardarformulario($cmb_anio,$cmb_tipocarga,$cmb_catatividad,$cmb_detactiv,$cmb_tipoencuesta,$nombre,$ruta){ 

			if($cmb_detactiv ==null){
				$cmb_detactiv =0;
			} 

			$sentencia ="select \"SYSTEM\".insertar_formulario('".$cmb_anio."','".$cmb_tipoencuesta."','".$cmb_tipocarga."','".$cmb_catatividad."','".$cmb_detactiv."','".$nombre."','".$ruta."','".$_SESSION['id']."');";

			$datos = $this->ejecutarConsulta($sentencia);
			$datos = (int) $datos->fetchColumn(); 

			return $datos;
		}

		//Funcion para cambiar de estado al catalogo 
		public function clonar($idformulario,$idformnuevo,$anio){ 

		 
				$sentencia ="select \"SYSTEM\".clonarformulario('".$anio."','".$idformulario."','".$idformnuevo."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute();  

			return $sql;
		}




    }