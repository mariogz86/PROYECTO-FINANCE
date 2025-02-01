<?php

	namespace app\controllers;
	use app\models\mainModel;

	class vercargaController extends mainModel{

        //funcion para obtener valor de variables por formulario y archivofuente
        public function obtenerarchivosfuente_por_idformulario($idform,$idarchivo){
                    
            $consulta_datos="select * from \"SYSTEM\".OBTENER_VALORVARIABLES  where id_formulario='".$idform."' and id_archivofuente= '".$idarchivo."' ;"; 

            $datos = $this->ejecutarConsulta($consulta_datos);
            $datos = $datos->fetchAll();

            return $datos;
        }

         

    }