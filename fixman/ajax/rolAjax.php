<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
require_once "../autoload.php";
 
 
 
	//incializando controlador
	use app\controllers\rolController;
	$insrol = new rolController();

	//Metodo GET para la carga del grid en la pantalla
	if(isset($_GET['cargagrid']))
	{
		//llamada al metodo de carga del controlador
		$result =$insrol->listarrol();

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
        $result =$insrol->BuscarRol();
        if($result){
            $alerta=[
				"classform"=>".FormularioAjax",
                "tipo"=>"limpiar",
                "titulo"=>"Duplicate role",
                "texto"=>"The role name already exists in the system",
                "icono"=>"warning"
            ];	

        }
        else
        {
		//se invoca el metodo de guardar del controlador
		$result =$insrol->guardar();
		//resultado que se envia al metodo POST
		if($result){
			if ($_POST["idrol"]=="0"){
			$alerta=[
				"classform"=>".FormularioAjax",
				"tipo"=>"limpiar",
				"titulo"=>"Registered role",
				"texto"=>"The record was saved successfully",
				"icono"=>"success"
			];}
			else{
				$alerta=[
					"classform"=>".FormularioAjax",
					"tipo"=>"limpiar",
					"titulo"=>"Updated role",
					"texto"=>"The record was updated successfully.",
					"icono"=>"success"
				];	
			}
		}else{
			if ($_POST["idrol"]=="0"){
				$alerta=[
					"classform"=>".FormularioAjax",
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"The role could not be registered, please try again.",
					"icono"=>"error"
				];
			}
				else{
					$alerta=[
						"classform"=>".FormularioAjax",
						"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"The role could not be updated, please try again.",
					"icono"=>"error"
					];	
				}
			
		}
    }


		echo json_encode($alerta); 
	}

	//Metodo POST para activar e inactivar el estado del registro
	if(isset($_POST['modulo_rol']))
	{

        if($_POST['modulo_rol']=="inactivar"){
            $result =$insrol->validar_inactivar();
            if($result){
                $alerta=[
					"classform"=>".FormularioAjax",
                    "tipo"=>"limpiar",
                    "titulo"=>"Inactivate",
                    "texto"=>"The role cannot be inactivated and is assigned to one or more users.",
                    "icono"=>"warning"
                ];	
    
            }else{
                $result =$insrol->cambiarestado(0);

                $alerta=[
					"classform"=>".FormularioAjax",
					"tipo"=>"limpiar",
					"titulo"=>"Deactivate registration",
					"texto"=>"The registration was successfully deactivated",
					"icono"=>"success"
				];
            }
		}else{

		//llamada al metodo del controlador 1 para activar y 0 para inactivar el estado del registro
		if($_POST['modulo_rol']=="activar"){
			$result =$insrol->cambiarestado(1);
		}
		
		//resultado que recive el metodo POST en la pantalla
		if($result){
			if($_POST['modulo_rol']=="activar"){
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
    }
		echo json_encode($alerta); 
	}