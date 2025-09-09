<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
require_once "../autoload.php";

use app\controllers\logerrorController;
$log = new logerrorController();

	//inicializar controlador
	use app\controllers\formularioController;
	$insformulario = new formularioController();
	//Metodo GET para cargar el combo de Detalle de actividad
	if(isset($_GET['Cargardetalleactividad'])<>"0")
	{
		try {
		//metodo del controlador
		$result =$insformulario->listardetalleactividad($_GET['Cargardetalleactividad']);
		//resultado que recibe el metodo GET invocado
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
		} catch (Exception  $e) {
			$res = array(
				'status' => 404,
				'message' =>  'No se encontro informacion'
			);

			$log->guardarlog($e->getMessage());
			echo json_encode($res);
		}
	}

	//Metodo GET para la carga del grid en la pantalla
	if(isset($_GET['cargagrid']))
	{
		try	{
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
	} catch (Exception  $e) {
		$res = array(
			'status' => 404,
			'message' =>  'No se encontro informacion'
		);

		$log->guardarlog($e->getMessage());
		echo json_encode($res);
	}
		
	}

	//Metodo POST para el guardado de un registro
	if(isset($_POST['modulo_Opcion'])=="registrar")
	{
		try {
					// validemos la ruta si es valida
					if (!is_dir( trim($_POST["ruta"]))) {
						$alerta=[
							"tipo"=>"simple",
							"titulo"=>"Error",
							"texto"=>"Ruta indicada no existe, por favor validar.",
							"icono"=>"error"
						];
					}  else{ 

				// validemos si el archivo existe
					$nombrearchivo=$_POST["nombre"];
					if (!file_exists(trim($_POST["ruta"])."/".$nombrearchivo)) {
						$alerta=[
							"tipo"=>"simple",
							"titulo"=>"Error",
							"texto"=>"Nombre del archivo no existe, por favor validar.",
							"icono"=>"error"
						];
					}else{
						//proceso para validar si el archivo asociado es de tipo excel
						$validarextension=false;
						$dir=trim($_POST["ruta"]); 
						$iterator = new FilesystemIterator("$dir");
						foreach($iterator as $entry) {
							if($entry->getFileName()==$nombrearchivo ){
								if ($entry->getExtension()=="xlsx"){
									$validarextension=true;
								}
							}
						}
						//si el archivo no es de tipo excel se envia el mensaje
						if (!$validarextension){
							$alerta=[
								"tipo"=>"simple",
								"titulo"=>"Error",
								"texto"=>"El tipo de archivo no es permitido,por favor validar.",
								"icono"=>"error"
							]; 
						}
						else{

						
						//validar si el formulario que se quiere guardar ya existe
						$validarduplicado =$insformulario->buscarformulario();
						//mensaje que se envia si el registro ya existe
						if($validarduplicado){
							$alerta=[	
								"tipo"=>"simple",
								"titulo"=>"Advertencia",
								"texto"=>"Los datos del formulario ya existen en la base de datos.",
								"icono"=>"warning"
							];

						}else{
									//se guarda el registo en caso de que no este duplicado
									$result =$insformulario->guardar(); 
									//resultado que se envia al Metodo POST invocado
										if($result){
											if ($_POST["idformulario"]=="0"){
											$alerta=[
												"tipo"=>"limpiar",
												"titulo"=>"Formulario",
												"texto"=>"El registro se guardo con éxito",
												"icono"=>"success"
											];}
											else{
												$alerta=[
													"tipo"=>"limpiar",
													"titulo"=>"Formulario",
													"texto"=>"El registro se actualizo con éxito",
													"icono"=>"success"
												];	
											}
										}else{
											if ($_POST["idformulario"]=="0"){
												$alerta=[
													"tipo"=>"simple",
													"titulo"=>"Error",
													"texto"=>"No se pudo registrar el Formulario, por favor intente nuevamente",
													"icono"=>"error"
												];
											}
												else{
													$alerta=[
														"tipo"=>"simple",
													"titulo"=>"Error",
													"texto"=>"No se pudo actualizar el Formulario, por favor intente nuevamente",
													"icono"=>"error"
													];	
												}
											
										}
									}
								}
							}
				}
			} catch (Exception  $e) {
				$alerta=[
					"tipo"=>"simple",
				"titulo"=>"Error",
				"texto"=>"Error consulte con el administrador",
				"icono"=>"error"
				];	
		
				$log->guardarlog($e->getMessage());
				 
			}

		echo json_encode($alerta); 
	}

	//Metodo POST para cambio de ruta
	if(isset($_POST['hdf_cambioruta'])=="cambioruta")
	{
			try {
					//se valida si la ruta indicada existe
					if (!is_dir( TRIM($_POST["nuevaruta"]))) {
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
			} catch (Exception  $e) {
				$alerta=[
					"tipo"=>"simple",
				"titulo"=>"Error",
				"texto"=>"Error consulte con el administrador",
				"icono"=>"error"
				];	

				$log->guardarlog($e->getMessage());
				
			}

		echo json_encode($alerta); 
		 
	}