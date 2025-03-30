<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
require_once "../autoload.php";

	//inicializar controlador
	use app\controllers\usuarioController;
	$insformulario = new usuarioController();
	 

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
				"titulo"=>"User",
				"texto"=>"The username already exists in the system.",
				"icono"=>"warning"
			];	
		}
		else{
		//se invoca el metodo de guardar del controlador
		$result =$insformulario->guardar();
		//resultado que se envia al metodo POST
		if($result>0){
			if ($_POST["idusuario"]=="0"){
			$alerta=[
				"classform"=>".FormularioAjax",
				"tipo"=>"limpiar",
				"titulo"=>"Registered user",
				"texto"=>"The record was saved successfully",
				"icono"=>"success"
			];}
			else{
				$alerta=[
					"classform"=>".FormularioAjax",
					"tipo"=>"limpiar",
					"titulo"=>"Updated user",
					"texto"=>"The record was updated successfully.",
					"icono"=>"success"
				];	
			}
		}else{
			if ($_POST["idusuario"]=="0"){
				$alerta=[
					"classform"=>".FormularioAjax",
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"User registration failed, please try again.",
					"icono"=>"error"
				];
			}
				else{
					$alerta=[
						"classform"=>".FormularioAjax",
						"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"Failed to update User, please try again",
					"icono"=>"error"
					];	
				}
			
		}
	}
		echo json_encode($alerta); 
	}

	 
	//Metodo POST para el guardado de un registro
	
	if(isset($_POST['modulo_usuario']))
	{

		if($_POST['modulo_usuario']=="bloquear"){

			$result =$insformulario->bloquear(1);

			$alerta=[
				"classform"=>".FormularioAjax",
				"tipo"=>"limpiar",
				"titulo"=>"User",
				"texto"=>"The user was successfully blocked",
				"icono"=>"success"
			];
		}

		if($_POST['modulo_usuario']=="desbloquear"){

			$result =$insformulario->bloquear(0);

			$alerta=[
				"classform"=>".FormularioAjax",
				"tipo"=>"limpiar",
				"titulo"=>"User",
				"texto"=>"The user was successfully unlocked",
				"icono"=>"success"
			];
		}

		if($_POST['modulo_usuario']=="inactivar"){

			$result =$insformulario->cambiarestado(0);
			$alerta=[
				"classform"=>".FormularioAjax",
				"tipo"=>"limpiar",
				"titulo"=>"Deactivate registration",
				"texto"=>"The registration was successfully deactivated",
				"icono"=>"success"
			];
		}

		
		if($_POST['modulo_usuario']=="activar"){

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
 