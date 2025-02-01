<?php

	namespace app\controllers;
	use app\models\mainModel;

	class aplicarvalidacionController extends mainModel{

        //funcion para obtener las boletas cargadas para un formulario
        public function obtenerboletas_por_idformulario($id){
                    
            $consulta_datos="select distinct boleta from \"SYSTEM\".valorvariable where id_formulario='".$id."' and
             boleta not in (select distinct boleta  from \"SYSTEM\".VALIDACIONAPLICADA where id_formulario='".$id."') order by boleta asc"; 

            $datos = $this->ejecutarConsulta($consulta_datos);
            $datos = $datos->fetchAll();

            return $datos;
        }

         //funcion para obtener las boletas cargadas para un formulario
         public function obtenerboletas_por_boleta($id){
                    
            $consulta_datos="select distinct boleta from \"SYSTEM\".valorvariable where boleta='".$id."' order by boleta asc"; 

            $datos = $this->ejecutarConsulta($consulta_datos);
            $datos = $datos->fetchAll();

            return $datos;
        }

        //funcion para obtener validaciones aplicadas por formulario
        public function obtenervalidacionesaplicadas_formulario($id){
                    
            $consulta_datos="select cv.descripcion,va.* from \"SYSTEM\".VALIDACIONAPLICADA va inner join
                            \"SYSTEM\".catalogovalor cv on cv.nombre=va.tipovalidacion where va.id_formulario='".$id."' order by va.id_validacionaplicada asc"; 

            $datos = $this->ejecutarConsulta($consulta_datos);
            $datos = $datos->fetchAll();

            return $datos;
        }

        //funcion para listar las validaciones guardadas al sistema
        public function listarvalidaciones_formulario($id){
			
			$consulta_datos="select * from \"SYSTEM\".obtener_validaciones where  id_formulario='".$id."' and u_estado=1 "; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}

        //funcion para listar las validaciones guardadas al sistema
        public function obtenervalidacionparametros($idform,$boleta,$idval){
			
			$consulta_datos="select * from \"SYSTEM\".obetnervalidacionesboleta('".$idform."','".$boleta."','".$idval."'); "; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}

        function parseFloat($value) {
            return floatval( $value);
        }

          //funcion para guardar las variables por envio de JSON
		public function guardar($json){
			
			$consulta_datos="select \"SYSTEM\".GUARDARVALIDACIONESAPLICADAS('".$json."',".$_SESSION['id'].");"; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}

         //funcion para obtener los datos de la validacion
         public function OBETNERDATOSVALIDACION($idval,$boleta){
			
			$consulta_datos="select * from \"SYSTEM\".OBETNERDATOSVALIDACION('".$idval."','".$boleta."'); "; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}

}