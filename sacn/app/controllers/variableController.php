<?php

	namespace app\controllers;
	use app\models\mainModel;

	class variableController extends mainModel{
		//funcion para listar las variables de la base de datos 
		//segun formulario, hoja y archivo fuente
        public function listarvariable(){
			
			$consulta_datos="select * from \"SACNSYS\".obtener_variable where id_formulario=".$_GET["formulario"]." and id_hoja=".$_GET["hoja"]." and id_archivofuente=".$_GET["archivo"]." "; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}
		//funcion para obtener las hojas del formulario
        public function obtenerhojas($idform){
			
			$consulta_datos="select * from \"SACNSYS\".obtener_hoja where id_formulario=".$idform." and u_estado=1"; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}
		//funcion para obtener los archivos fuentes del formulario
        public function obtenerarchivosfuentes($id){
			
			$consulta_datos="select * from \"SACNSYS\".obtener_archivofuente  where id_formulario='".$id."' and u_estado=1;"; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}

		//funcion para obtener archivo fuente por su ID
        public function obtenerarchivosfuente_por_id($id){
			
			$consulta_datos="select * from \"SACNSYS\".obtener_archivofuente  where id_archivofuente='".$id."';"; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}
		//funcion para obtener el formulario por su ID
		public function obtenerformulario_porid($id){
			
			$consulta_datos="select * from \"SACNSYS\".obtener_formulario  where id_formulario='".$id."';"; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}
		//funcion para guardar las variables por envio de JSON
		public function guardar($json){
			
			$consulta_datos="select \"SACNSYS\".guardarvariables(".$_POST["formulario"].",".$_POST["hoja"].",".$_POST["archivo"].",".$_POST["coincidir"].",'".$json."',".$_SESSION['id'].");"; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}
		//funcion para actualizar las variables por metodo JSON
		public function actualizarvar($json){
			
			$consulta_datos="select \"SACNSYS\".actualizarvariables('".$json."',".$_POST["coincidir"].",".$_SESSION['id'].");"; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}

		//Funcion para cambiar de estado al catalogo 
		public function cambiarestado($estado){ 

			$idcat = $_POST["id_variable"];
				$sentencia ="select \"SACNSYS\".cambiarestado_variable('".$idcat."','".$estado."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute(); 

		 return $sql;
		}


}