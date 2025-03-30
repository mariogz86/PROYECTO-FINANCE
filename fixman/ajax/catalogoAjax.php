<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
require_once "../autoload.php";
 
 
 
	//incializando controlador
	use app\controllers\catalogoController;
	$inscatalogo = new catalogoController();

	//Metodo GET para la carga del grid en la pantalla
	if(isset($_GET['cargagrid']))
	{
		//llamada al metodo de carga del controlador
		$result =$inscatalogo->listarcatalogo();

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
	if(isset($_POST['modulo_Opcion']))
	{
		//se invoca el metodo de guardar del controlador
		$result =$inscatalogo->guardar();
		//resultado que se envia al metodo POST
		if($result){
			if ($_POST["idcatalogo"]=="0"){
			$alerta=[
				"classform"=>".FormularioAjax",
				"tipo"=>"limpiar",
				"titulo"=>"Registered catalog",
				"texto"=>"The record was saved successfully",
				"icono"=>"success"
			];}
			else{
				$alerta=[
					"classform"=>".FormularioAjax",
					"tipo"=>"limpiar",
					"titulo"=>"Updated catalog",
					"texto"=>"The record was updated successfully.",
					"icono"=>"success"
				];	
			}
		}else{
			if ($_POST["idcatalogo"]=="0"){
				$alerta=[
					"classform"=>".FormularioAjax",
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"The catalog could not be registered, please try again.",
					"icono"=>"error"
				];
			}
				else{
					$alerta=[
						"classform"=>".FormularioAjax",
						"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"The catalog could not be updated, please try again.",
					"icono"=>"error"
					];	
				}
			
		}

		echo json_encode($alerta); 
	}

	//Metodo POST para activar e inactivar el estado del registro
	if(isset($_POST['modulo_catalogo']))
	{
		//llamada al metodo del controlador 1 para activar y 0 para inactivar el estado del registro
		if($_POST['modulo_catalogo']=="activar"){
			$result =$inscatalogo->cambiarestado(1);
		}else{
			$result =$inscatalogo->cambiarestado(0);
		}
		
		//resultado que recive el metodo POST en la pantalla
		if($result){
			if($_POST['modulo_catalogo']=="activar"){
				$alerta=[
					"classform"=>".FormularioAjax",
					"tipo"=>"limpiar",
					"titulo"=>"Activate registration",
					"texto"=>"Registration was successfully activated",
					"icono"=>"success"
				];
			}
			else{
				$alerta=[
					"classform"=>".FormularioAjax",
					"tipo"=>"limpiar",
					"titulo"=>"Deactivate registration",
					"texto"=>"The registration was successfully deactivated",
					"icono"=>"success"
				];
			}
			

		}else{
			$alerta=[
				"classform"=>".FormularioAjax",
				"tipo"=>"simple",
			"titulo"=>"Error",
			"texto"=>"The requested action could not be performed, please try again.",
			"icono"=>"error"
			];	
		} 
		echo json_encode($alerta); 
	}