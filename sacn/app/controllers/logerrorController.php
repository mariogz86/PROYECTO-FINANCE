<?php

	namespace app\controllers;
	use app\models\mainModel;

	//delcaracion de funciones del controlador
	class logerrorController extends mainModel{
		//funcion para obtener el formulario y buscar las hojas asociadas
		public function guardarlog($mensaje){
			$log_message = "[" . date("Y-m-d H:i:s") . "] [".$mensaje."]\n\n"; 
			
              error_log( $log_message  , 3, '../Error/log.txt');	
			  //echo( $log_message);	
			 
		}

			public function guardarlogvistas($mensaje){
			$log_message = "[" . date("Y-m-d H:i:s") . "] [".$mensaje."]\n\n"; 
			
              error_log( $log_message  , 3, 'Error/log.txt');		
			  //echo( $log_message);
			 
		}

		


    }