<?php

	namespace app\controllers;
	use app\models\mainModel;

	class vercargaController extends mainModel{

        //funcion para obtener valor de variables por formulario y archivofuente
        public function obtenerarchivosfuente_por_idformulario($idform,$idarchivo){
                    
            $consulta_datos="select * from \"SACNSYS\".OBTENER_VALORVARIABLES  where id_formulario='".$idform."' and id_archivofuente= '".$idarchivo."' ;"; 

            $datos = $this->ejecutarConsulta($consulta_datos);
            $datos = $datos->fetchAll();

            return $datos;
        }


        public function obtenercargadetalle($idform,$idarchivo){
                    
            $consulta_datos="SELECT jsonb_agg(json_data) AS datosjson FROM \"SACNSYS\".jsondetalles  where CAST(json_data->>'id_formulario' as integer)='".$idform."' and CAST(json_data->>'id_archivofuente' as integer)= '".$idarchivo."' ;"; 

            $datos = $this->ejecutarConsulta($consulta_datos);
            $datos = $datos->fetchAll();

            return $datos;
        }

         

    }