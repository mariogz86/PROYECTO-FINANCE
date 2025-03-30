<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
require_once "../autoload.php";

	//inicializar controlador
	use app\controllers\companyController;
	$insformulario = new companyController();
	 

	//Metodo GET para la carga del grid en la pantalla
	if(isset($_GET['cargagrid']))
	{
		//metodo del controlador
		$result =$insformulario->listarformulario();
		//resultado que se envita al Metodo GET invocado
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

		$buscarUsuario =$insformulario->BuscarUsuario();

		if($buscarUsuario>0){
			$alerta=[
				"classform"=>".FormularioAjax",
				"tipo"=>"simple",
				"titulo"=>"Company",
				"texto"=>"The company name already exists in the system.",
				"icono"=>"warning"
			];	
		}
		else{
		//se invoca el metodo de guardar del controlador
		$result =$insformulario->guardar();
		//resultado que se envia al metodo POST
		if($result>0){
			if ($_POST["idCompany"]=="0"){
			$alerta=[
				"classform"=>".FormularioAjax",
				"tipo"=>"limpiar",
				"titulo"=>"Company",
				"texto"=>"The record was saved successfully",
				"icono"=>"success"
			];}
			else{
				$alerta=[
					"classform"=>".FormularioAjax",
					"tipo"=>"limpiar",
					"titulo"=>"Company",
					"texto"=>"The registry was updated successfully",
					"icono"=>"success"
				];	
			}
		}else{
			if ($_POST["idCompany"]=="0"){
				$alerta=[
					"classform"=>".FormularioAjax",
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"The record could not be saved, please try again",
					"icono"=>"error"
				];
			}
				else{
					$alerta=[
						"classform"=>".FormularioAjax",
						"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"The record could not be updated, please try again",
					"icono"=>"error"
					];	
				}
			
		}
	}
		echo json_encode($alerta); 
	}

	 

	//Metodo POST para el guardado de un registro
	
	if(isset($_POST['modulo_company']))
	{

		 
	 
		if($_POST['modulo_company']=="inactivar"){

			$result =$insformulario->cambiarestado(0);
			$alerta=[
				"classform"=>".FormularioAjax",
				"tipo"=>"limpiar",
				"titulo"=>"Deactivate registration",
				"texto"=>"The registration was successfully deactivated",
				"icono"=>"success"
			];
		}

		
		if($_POST['modulo_company']=="activar"){

			$result =$insformulario->cambiarestado(1);
			$alerta=[
				"classform"=>".FormularioAjax",
				"tipo"=>"limpiar",
				"titulo"=>"Activate registration",
				"texto"=>"Registration was successfully activated",
				"icono"=>"success"
			];
		}
		

		echo json_encode($alerta); 

	}
 