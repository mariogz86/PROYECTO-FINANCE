<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
require_once "../autoload.php";
 

 
	//incializamos el controlador
	use app\controllers\valcatalogoController;
	$inscatalogo = new valcatalogoController();
 

 
	//Metodo GET para la carga del grid en la pantalla
	if(isset($_GET['cargagrid']))
	{
		//llamada al metodo en el controlador
		$result =$inscatalogo->listarvalcatalogo();
		//resultado que se envia al metodo invocado
		if($result )
		{ 
			$res = array (
				'status' => 200,
				'message' => 'carga usuarios correcta',
				'data' => $result
					);
			echo json_encode($res);
		}
		else
		{
			$res = array (
				'status' => 404,
				'message' =>  'No se encontro informacion'
				);
			echo json_encode($res); 
		}    
		
	}
	//Metodo POST para el guardado de un registro en la pantalla
	if(isset($_POST['modulo_Opcion']))
	{
		//llamada al metodo del controlador
		$result =$inscatalogo->guardar();	
		//resultado que se envia al metodo invocado
		if($result){
			if ($_POST["idcatalogovalor"]=="0"){
			$alerta=[
				"tipo"=>"limpiar",
				"titulo"=>"Valor de catálogo",
				"texto"=>"El registro se guardo con éxito",
				"icono"=>"success"
			];}
			else{
				$alerta=[
					"tipo"=>"limpiar",
					"titulo"=>"Valor de catálogo",
					"texto"=>"El registro se actualizo con éxito",
					"icono"=>"success"
				];	
			}
		}else{
			if ($_POST["idcatalogovalor"]=="0"){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No se pudo registrar el valor para el  catálogo, por favor intente nuevamente",
					"icono"=>"error"
				];
			}
				else{
					$alerta=[
						"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No se pudo actualizar el valor para el catálogo, por favor intente nuevamente",
					"icono"=>"error"
					];	
				}
			
		}

		echo json_encode($alerta); 
	}

	//Metodo POST para inactivar el estado de un registro
	if(isset($_POST['accion']))
	{
		//llamada al metodo del controlador 1 para activar y 0 para inactivar el estado del registro
		if($_POST['accion']=="activar"){
			$result =$inscatalogo->cambiarestado(1);
		}else{
			$result =$inscatalogo->cambiarestado(0);
		}
		
		//resultado que se envia al metodo invocado
		if($result){
			if($_POST['accion']=="activar"){
				$alerta=[
					"tipo"=>"limpiar",
					"titulo"=>"Activar registro",
					"texto"=>"El registro se activo con éxito",
					"icono"=>"success"
				];
			}
			else{
				$alerta=[
					"tipo"=>"limpiar",
					"titulo"=>"Inactivar registro",
					"texto"=>"El registro se Inactivo con éxito",
					"icono"=>"success"
				];
			} 
		}else{
			$alerta=[
				"tipo"=>"simple",
			"titulo"=>"Error",
			"texto"=>"No se pudo realizar la acción solicitada, por favor intente nuevamente",
			"icono"=>"error"
			];	
		} 
		echo json_encode($alerta); 
	}