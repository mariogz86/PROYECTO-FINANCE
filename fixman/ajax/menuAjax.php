<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
require_once "../autoload.php";
 
 
 
	//incializando controlador
	use app\controllers\menuController;
	$insmenu = new menuController();

	//Metodo GET para la carga del grid en la pantalla
	if(isset($_GET['cargagrid']))
	{
		//llamada al metodo de carga del controlador
		$result =$insmenu->listarmenu();

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
        $result =$insmenu->Buscarmenu();
        if($result){
            $alerta=[
				"classform"=>".FormularioAjax",
                "tipo"=>"limpiar",
                "titulo"=>"duplicate menu",
                "texto"=>"The menu name already exists in the system",
                "icono"=>"warning"
            ];	

        }
        else
        {
		//se invoca el metodo de guardar del controlador
		$result =$insmenu->guardar();
		//resultado que se envia al metodo POST
		if($result){
			if ($_POST["idmenu"]=="0"){
			$alerta=[
				"classform"=>".FormularioAjax",
				"tipo"=>"limpiar",
				"titulo"=>"registered menu",
				"texto"=>"The record was saved successfully",
				"icono"=>"success"
			];}
			else{
				$alerta=[
					"classform"=>".FormularioAjax",
					"tipo"=>"limpiar",
					"titulo"=>"updated menu",
					"texto"=>"The record was updated successfully.",
					"icono"=>"success"
				];	
			}
		}else{
			if ($_POST["idmenu"]=="0"){
				$alerta=[
					"classform"=>".FormularioAjax",
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"The menu could not be registered, please try again.",
					"icono"=>"error"
				];
			}
				else{
					$alerta=[
						"classform"=>".FormularioAjax",
						"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"The menu could not be updated, please try again.",
					"icono"=>"error"
					];	
				}
			
		}
    }


		echo json_encode($alerta); 
	}

	//Metodo POST para activar e inactivar el estado del registro
	if(isset($_POST['modulo_menu']))
	{

        if($_POST['modulo_menu']=="inactivar"){
            $result =$insmenu->validar_inactivar();
            if($result){
                $alerta=[
					"classform"=>".FormularioAjax",
                    "tipo"=>"limpiar",
                    "titulo"=>"Inactivate",
                    "texto"=>"The menu cannot be inactivated if it is assigned to one or more roles.",
                    "icono"=>"warning"
                ];	
    
            }else{
                $result =$insmenu->cambiarestado(0);

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
		if($_POST['modulo_menu']=="activar"){
			$result =$insmenu->cambiarestado(1);
		}
		
		//resultado que recive el metodo POST en la pantalla
		if($result){
			if($_POST['modulo_menu']=="activar"){
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