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

	
	//Metodo POST para el registro a guardar en la pantalla
	if(isset($_POST['modulo_Opcion']))
	{
		//se invoca el metodo de guardar del controlador
		$result =$insformulario->guardar();
		//resultado que se envia al metodo POST
		if($result>0){
			if ($_POST["idusuario"]=="0"){
			$alerta=[
				"tipo"=>"limpiar",
				"titulo"=>"Usuario registrado",
				"texto"=>"El registro se guardo con éxito",
				"icono"=>"success"
			];}
			else{
				$alerta=[
					"tipo"=>"limpiar",
					"titulo"=>"Usuario actualizado",
					"texto"=>"El registro se actualizo con éxito",
					"icono"=>"success"
				];	
			}
		}else{
			if ($_POST["idusuario"]=="0"){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No se pudo registrar el Usuario, por favor intente nuevamente",
					"icono"=>"error"
				];
			}
				else{
					$alerta=[
						"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No se pudo actualizar el Usuario, por favor intente nuevamente",
					"icono"=>"error"
					];	
				}
			
		}

		echo json_encode($alerta); 
	}

	//Metodo POST para cambio de ruta
	if(isset($_POST['hdf_cambioruta'])=="cambioruta")
	{
		//se valida si la ruta indicada existe
		if (!is_dir( $_POST["nuevaruta"])) {
			$alerta=[
				"tipo"=>"simple",
				"titulo"=>"Error",
				"texto"=>"Ruta indicada no existe, por favor validar.",
				"icono"=>"error"
			];
		}else{
			//metodo del controlador para guardar el cambio de ruta
			$result =$insformulario->guardar_cambioruta(); 

			//resultado que se envia al guardar el registro
			if($result){
					$alerta=[
						"tipo"=>"limpiar",
						"titulo"=>"Cambio de ruta",
						"texto"=>"Ruta actualizada para los formularios seleccionados",
						"icono"=>"success"
					];	
				}else{
					$alerta=[
						"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No se pudo actualizar la ruta, por favor intente nuevamente",
					"icono"=>"error"
					];	
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
				"tipo"=>"limpiar",
				"titulo"=>"Usuario",
				"texto"=>"El usuario se bloqueo con éxito",
				"icono"=>"success"
			];
		}

		if($_POST['modulo_usuario']=="desbloquear"){

			$result =$insformulario->bloquear(0);

			$alerta=[
				"tipo"=>"limpiar",
				"titulo"=>"Usuario",
				"texto"=>"El usuario se desbloqueo con éxito",
				"icono"=>"success"
			];
		}

		if($_POST['modulo_usuario']=="inactivar"){

			$result =$insformulario->cambiarestado(0);
			$alerta=[
				"tipo"=>"limpiar",
				"titulo"=>"Inactivar registro",
				"texto"=>"El registro se Inactivo con éxito",
				"icono"=>"success"
			];
		}

		
		if($_POST['modulo_usuario']=="activar"){

			$result =$insformulario->cambiarestado(1);
			$alerta=[
				"tipo"=>"limpiar",
				"titulo"=>"Activar registro",
				"texto"=>"El registro se activo con éxito",
				"icono"=>"success"
			];
		}
		

		echo json_encode($alerta); 

	}
 