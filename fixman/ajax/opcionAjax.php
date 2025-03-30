<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
require_once "../autoload.php";
 
 
 
	//incializando controlador
	use app\controllers\opcionController;
	$insopcion = new opcionController();

	//Metodo GET para la carga del grid en la pantalla
	if(isset($_GET['cargagrid']))
	{
		//llamada al metodo de carga del controlador
		$result =$insopcion->listar();

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
	if(isset($_POST['modulo_Opcionmenu']))
	{
        $result =$insopcion->Buscaropcion();
        if($result){
            $alerta=[
				"classform"=>".FormularioAjax",
                "tipo"=>"limpiar",
                "titulo"=>"menu option",
                "texto"=>"The option name already exists in the system",
                "icono"=>"warning"
            ];	

        }
        else
        {
		//se invoca el metodo de guardar del controlador
		$result =$insopcion->guardar();
		//resultado que se envia al metodo POST
		if($result){
			if ($_POST["idopcion"]=="0"){
			$alerta=[
				"classform"=>".FormularioAjax",
				"tipo"=>"limpiar",
				"titulo"=>"menu option",
				"texto"=>"The record was saved successfully",
				"icono"=>"success"
			];}
			else{
				$alerta=[
					"classform"=>".FormularioAjax",
					"tipo"=>"limpiar",
					"titulo"=>"menu option",
					"texto"=>"The record was updated successfully.",
					"icono"=>"success"
				];	
			}
		}else{
			if ($_POST["idopcion"]=="0"){
				$alerta=[
					"classform"=>".FormularioAjax",
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"The option could not be registered, please try again.",
					"icono"=>"error"
				];
			}
				else{
					$alerta=[
						"classform"=>".FormularioAjax",
						"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"The option could not be updated, please try again.",
					"icono"=>"error"
					];	
				}
			
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
					"titulo"=>"Deactivate registration",
					"texto"=>"The registration was successfully deactivated",
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