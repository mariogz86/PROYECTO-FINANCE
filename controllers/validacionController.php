<?php

	namespace app\controllers;
	use app\models\mainModel;

	class validacionController extends mainModel{
		//funcion para listar las validaciones guardadas al sistema
        public function listar(){
			
			$consulta_datos="select * from \"SYSTEM\".obtener_validaciones"; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}

		//funcion para listar los parametros de una validacion
        public function listarparametros(){
			
			$consulta_datos="select * from \"SYSTEM\".obtener_parametros where id_validacion='".$_GET["cargagridparametro"]."'"; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}

		//funcion para listar las variables asociadas a un archivo 
        public function listarvariable(){
			
			$consulta_datos="select id_variable,nombrevariable from \"SYSTEM\".obtener_variable where id_archivofuente='".$_GET["cargarvariables"]."' and id_formulario='".$_GET["formulario"]."' and u_estado=1;"; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}

		//funcion para verificar que el nombre de la validacion no exista
        public function buscarvalidacion(){
			$nombre = $_POST["nombre"];		
			$sentencia ="select \"SYSTEM\".buscarvalidacion('".$nombre."','".$_POST["idvalidacion"]."');";
			$sql=$this->ejecutarConsulta($sentencia);
			$existe = (int) $sql->fetchColumn(); 

			return $existe;
		}

		//Funcion para guardar los datos de la validación
		public function guardar(){	 
	
			
			$nombre = $_POST["nombre"];			
			$cmb_formulario = $_POST["cmb_formulario"];
			$cmb_tipvalidacion = $_POST["cmb_tipvalidacion"];
			$cmb_condicion = $_POST["cmb_condicion"]; 


			if ($_POST["idvalidacion"]=="0"){				
				$sentencia ="select \"SYSTEM\".insertar_validacion('".$nombre."','".$cmb_formulario."','".$cmb_tipvalidacion."','".$cmb_condicion."','".$_SESSION['id']."');";
				$sql=$this->ejecutarConsulta($sentencia);
				$total = (int) $sql->fetchColumn(); 
			}else{				
				$sentencia ="select \"SYSTEM\".actualizar_validacion('".$_POST["idvalidacion"]."','".$nombre."','".$cmb_formulario."','".$cmb_tipvalidacion."','".$cmb_condicion."','".$_SESSION['id']."');";
				$sql=$this->ejecutarConsulta($sentencia);
				$total = (int) $sql->fetchColumn(); 	

			} 

			return $total;
		}

		//Funcion para guardar los datos de la validación
		public function guardarvalidaciones($json){	 
	 			
				$sentencia ="select \"SYSTEM\".guardarvalidacionexcel('".$json."',".$_SESSION['id'].");"; 
				$sql=$this->ejecutarConsulta($sentencia);
 
			return $sql;
		}

			//Funcion para guardar los datos de parametros
			public function guardarparametrosexcel($json){	 
	 			
				$sentencia ="select \"SYSTEM\".guardarparametrosexcel('".$json."') ;"; 
				$sql=$this->ejecutarConsulta($sentencia);
 
			return $sql;
		}

			//funcion para validar si en la carga hay errores
			public function validarcargaparametros($json){
			
				$consulta_datos="select * from \"SYSTEM\".validarvariables('".$json."') where idvariable=0;"; 
	
				$datos = $this->ejecutarConsulta($consulta_datos);
				$datos = $datos->fetchAll();
	
				return $datos;
			}

			//Funcion para guardar los parametros de la validación
			public function guardarparametro(){	 
	
			
				$idvalidacionparametro = $_POST["idvalidacionparametro"];			
				$cmb_variable = $_POST["cmb_variable"];
				$cmb_parametro = $_POST["cmb_parametro"];
				$cmb_valorparam = $_POST["cmb_valorparam"]; 
	 			
					$sentencia ="select \"SYSTEM\".insertar_parametro('".$idvalidacionparametro."','".$cmb_variable."','".$cmb_parametro."','".$cmb_valorparam."','".$_SESSION['id']."');";
					$sql=$this->ejecutarConsulta($sentencia);
					$total = (int) $sql->fetchColumn(); 
			 
	
				return $total;
			}

			//Funcion para cambiar de estado de validacion 
		public function cambiarestado($estado){ 

			$idcat = $_POST["id_validacion"];
				$sentencia ="select \"SYSTEM\".cambiarestado_validacion('".$idcat."','".$estado."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute(); 

		 return $sql;
		}

			//Funcion para eliminar un registro de parametro
			public function eliminar(){ 

				$idcat = $_POST["id_parametro"];
					$sentencia ="select \"SYSTEM\".eliminar_parametro('".$idcat."');";
					$sql=$this->actualizarDatos($sentencia);
					$sql->execute(); 
	
			 return $sql;
			}

		//funcion para cambiar nombre a las validaciones seleccionados en la pantalla
		public function guardar_cambionombre(){
			  
			$idseleccionados = $_POST["hdf_seleccionados"];			 
			$nombre = trim($_POST["nombre"]); 
			$nombrenuevo = trim($_POST["nombrenuevo"]); 
			$sentencia ="select \"SYSTEM\".ACTUALIZAR_NOMBREVALIDACION('".$idseleccionados."','".$nombre."','".$nombrenuevo."','".$_SESSION['id']."');";
			$sql=$this->actualizarDatos($sentencia);
 			$sql->execute();	  
		 return $sql;
		}
	 
}