<?php

	namespace app\controllers;
	use app\models\mainModel;

	class cargaController extends mainModel{

        //funcion para obtener archivo fuente por su ID formulario
        public function obtenerarchivosfuente_por_idformulario($id){
                    
            $consulta_datos="select * from \"SYSTEM\".obtener_archivofuente  where id_formulario='".$id."' and u_estado=1;"; 

            $datos = $this->ejecutarConsulta($consulta_datos);
            $datos = $datos->fetchAll();

            return $datos;
        }

        //funcion para obtener los archivos fuentes del formulario
        public function obtenerarchivosfuentes_poridarchivo($id){
			
          $consulta_datos="select * from \"SYSTEM\".obtener_archivofuente  where id_archivofuente='".$id."' and u_estado=1;"; 

          $datos = $this->ejecutarConsulta($consulta_datos);
          $datos = $datos->fetchAll();

          return $datos;
        }

        //funcion para guardar las variables por envio de JSON
		public function guardar($json,$nuevas){
			
			$consulta_datos="select \"SYSTEM\".GUARDARVALORVARIABLES('".$json."',".$nuevas.",".$_SESSION['id'].");"; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}

    }