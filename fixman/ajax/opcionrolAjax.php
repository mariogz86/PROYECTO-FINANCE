<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
require_once "../autoload.php";
 
 
 
	//incializando controlador
	use app\controllers\opcionrolController;
	$insopcion = new opcionrolController();

	//Metodo GET para la carga del grid en la pantalla
	if(isset($_GET['cargagrid']))
	{
		//llamada al metodo de carga del controlador
		$result =$insopcion->listar($_GET['cargagrid']);

		//resultado que se envia al metodo GET
		if($result )
		{ 
			$res = array (
				'status' => 200,
				'message' => 'Successful data upload',
				'data' => $result
					);
			echo json_encode($res);
		}
		else
		{
			$res = array (
				'status' => 404,
				'message' =>  'No information found'
				);
			echo json_encode($res); 
		}    
		
	}

	//Metodo POST para el registro a guardar en la pantalla
	if(isset($_POST['modulo_Opcionrol']))
	{
        $result =$insopcion->Buscaropcion();
        if($result){
            $alerta=[
				"classform"=>".FormularioAjax",
                "tipo"=>"limpiar",
                "titulo"=>"role option",
                "texto"=>"Option is already associated with the role",
                "icono"=>"warning"
            ];	

        }
        else
        {
		//se invoca el metodo de guardar del controlador
		$result =$insopcion->guardar();
		//resultado que se envia al metodo POST
		if($result){ 
			$alerta=[
				"classform"=>".FormularioAjax",
				"tipo"=>"limpiar",
				"titulo"=>"menu option",
				"texto"=>"The record was saved successfully",
				"icono"=>"success"
			];

			$opcionmenu =$insopcion->Buscaropcionmenu();
			$idmenu=$opcionmenu[0]["id_menu"];

			$existemenurol =$insopcion->Buscarrolmenu($idmenu, $_POST["cmb_rol"]	);

			if($existemenurol[0]["count"]==0)
			{
				$result =$insopcion->guardarrolmenu($idmenu, $_POST["cmb_rol"]	);

			}
			 
		}else{ 
				$alerta=[
					"classform"=>".FormularioAjax",
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"The option could not be registered, please try again.",
					"icono"=>"error"
				];
		 
			
		}
    }


		echo json_encode($alerta); 
	}

	//Metodo POST para activar e inactivar el estado del registro
	if(isset($_POST['modulo_opcion']))
	{

        if($_POST['modulo_opcion']=="inactivar"){
            $result =$insopcion->cambiarestado(0);
            if($result){
               
              

                $alerta=[
					"classform"=>".FormularioAjax",
					"tipo"=>"limpiar",
					"titulo"=>"Delete record",
					"texto"=>"The record was successfully deleted",
					"icono"=>"success"
				];
            }
		}else{
            $result =$insopcion->cambiarestado(1);
		 
		//resultado que recive el metodo POST en la pantalla
		if($result){
			 
				$alerta=[
					"classform"=>".FormularioAjax",
					"tipo"=>"limpiar",
					"titulo"=>"Activate registration",
					"texto"=>"Registration was successfully activated",
					"icono"=>"success"
				];
			} 
		 
    }
		echo json_encode($alerta); 
	}