<?php

	namespace app\controllers;
	use app\models\mainModel;

	class generarController extends mainModel{

        //funcion para obtener las boletas cargadas para un formulario
        public function obtenerboletas_por_idformulario($id){
                    
            $consulta_datos="select distinct boleta from \"SYSTEM\".valorvariable where id_formulario='".$id."'   order by boleta asc"; 

            $datos = $this->ejecutarConsulta($consulta_datos);
            $datos = $datos->fetchAll();

            return $datos;
        }

         //funcion para obtener las boletas cargadas para un formulario
         public function obtenerdatos_formulario($id){
                    
            $consulta_datos="select * from \"SYSTEM\".obtener_formulario where id_formulario='".$id."'"; 

            $datos = $this->ejecutarConsulta($consulta_datos);
            $datos = $datos->fetchAll();

            return $datos;
        }


        //funcion para obtener las valores de las variables de una boleta para un formulario
        public function obtenervalorboleta_formulario($boleta,$formulario,$anio){
                    
            $consulta_datos="select * from \"SYSTEM\".OBTENER_VALORESBOLETA('".$boleta."','".$formulario."','".$anio."')"; 

            $datos = $this->ejecutarConsulta($consulta_datos);
            $datos = $datos->fetchAll();

            return $datos;
        }

        function limpiarletras($str) {
            return $str = intval(preg_replace('/[^0-9]+/', '', $str), 10); 
      
        }

        function limpiarnumeros($str) {
            return $str = preg_replace('/[0-9]+/', '', $str);
         
        }
        


        
}