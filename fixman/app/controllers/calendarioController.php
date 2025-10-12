<?php

	namespace app\controllers;
	use app\models\mainModel;
	//delcaracion de funciones para el controlador
	class calendarioController extends mainModel{
		//funcion para obtener los catalogos de la base de datos
        public function listarcatalogo(){
			$consulta_datos="SELECT c.id_cita, c.fecha, c.nota,cv.nombre estado, t.num_referencia FROM \"SYSTEM\".cita c
inner join \"SYSTEM\".trabajo t on t.id_trabajo=c.id_trabajo
inner join \"SYSTEM\".catalogovalor cv on cv.id_catalogovalor=t.id_estadotrabajo"; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}
		
    }