<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
require_once "../autoload.php";
use app\controllers\logerrorController;

$log = new logerrorController();

//incializando controlador
use app\controllers\archivoController;
$insarchivo = new archivoController();
 
 
//Metodos AJAX

//metodo get para cargar grid
if(isset($_GET['cargagrid']))
	{
		try {
		//llamada  a la funcion del controlador para cargar los archivos
		$result =$insarchivo->listararchivo();

		//estructura JSON que recibe el metodo GET como repuesta
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

//Metodo Get para cargar los archivos
    if(isset($_GET['buscararchivos']))
    {   
		try {
				//identificador del formulario		
				$idformulario=$_GET["buscararchivos"];
				//ruta 
				$dir=$_GET['ruta'];
				//Bandera que indica si la ruta es valida=1 no es valida=0
				$rutavalida=0;

				//variable que contendra los archivos ya asociados al formulario
				$result =$insarchivo->buscararachivo_porformulario($idformulario);
				
				//se valida si la ruta es valida o existe
				if (is_dir($dir))  { 
					//ruta existe
					$rutavalida=1;
					//arbimos el directorio
					$directorio = opendir($dir);
					//variable que contendra los archivos dentro de los directorio
					$archivos = array();
					//variable que contendra las carpetas dentro del directorio
					$carpetas = array();
				
				
				
					//Carpetas y Archivos a excluir
					$excluir = array('.', '..', 'index.php', 'favicon.ico','folder.png','file.png','.dropbox.cache','.dropbox');
					//ciclo para guardar las carpetas
					while ($f = readdir($directorio)) {
						if (is_dir("$dir/$f") && !in_array($f, $excluir)) {
							$carpetas[] = $f;
						}  
					}
				
					//cerramos el directorio
					closedir($directorio);
					//si existen carpetas dentro del directorio buscamos los archivos dentro de las carpetas
					if(!empty($carpetas)){
						foreach ($carpetas as $c) {
						
								$iterator = new FilesystemIterator("$dir/$c");
								foreach($iterator as $entry) {
									if ($entry->getExtension()=="sav"){
										$colors = array_column($result, 'nombrearchivo');
										$found_key = array_search($entry->getFilename(), $colors);
										$existe="No";
										if(is_numeric($found_key)){
											$existe="Si";
										}else{
											$archivos[] = array(
												'Nombre'=> $entry->getFilename(),
												'Ruta'=>"$dir/$c"
											);
										}

										
									}
								
								}
						}
					}//en caso de que no hay carpetas dentro de la ruta indicada buscamos los archivos en la misma
					else{
						$iterator = new FilesystemIterator("$dir");
						foreach($iterator as $entry) {
							if ($entry->getExtension()=="sav"){
								$colors = array_column($result, 'nombrearchivo');
								$found_key = array_search($entry->getFilename(), $colors);
								$existe="No";
								if(is_numeric($found_key)){
									$existe="Si";
								}else{
									$archivos[] = array(
										'Nombre'=> $entry->getFilename(),
										'Ruta'=>"$dir",
									);
								}
								
							}
						
						}
					}
				
					//respuesta  a la solicitud GET de carga de archivos
					$res = array (
						'status' => 200,
						'message' => 'Sectores cargados correctamente',
						'carpetas' => $carpetas,
						'archivos' => $archivos
							);
					echo json_encode($res);
				
				}
				else{
					//respuesta  a la solicitud GET de carga de archivos cuando la ruta indicada no existe
					$res = array (
						'status' => 404,
						'message' => 'No se encontro informacion'
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

	//Metodo POST para guardar los registros
    if(isset($_POST['modulo_Opcion'])=="registrar")
	{
		try{
        $archivos=$_POST['archivos'];

        $result =$insarchivo->guardar($archivos);

        if($result )
		{ 
			$res = array (
                'status' => 200, 
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
//Metodo POST para cambiar la ruta de la ruta de los archivos seleccionados en la pantalla
    if(isset($_POST['hdf_cambioruta'])=="cambioruta")
	{
		try{
		if (!is_dir( TRIM($_POST["nuevaruta"]))) {
			$alerta=[
				"tipo"=>"simple",
				"titulo"=>"Error",
				"texto"=>"Ruta indicada no existe, por favor validar.",
				"icono"=>"error"
			];
		}else{

			$result =$insarchivo->guardar_cambioruta(); 
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
		"texto"=>"Consulte con el administrador",
		"icono"=>"error"
		];	

		$log->guardarlog($e->getMessage());
		 
	}

		echo json_encode($alerta); 
		 
	}

//Metodo POST para cambiar de estado el archivo
    if(isset($_POST['modulo_archivo']))
	{
		try {
		//llamada al metodo del controlador 1= Activar 0=Inactivar
		if($_POST['modulo_archivo']=="activar"){
			$result =$insarchivo->cambiarestado(1);
		}else{
			$result =$insarchivo->cambiarestado(0);
		}
		
		//resultado que se envia al metodo invocado
		if($result){
			if($_POST['modulo_archivo']=="activar"){
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
	} catch (Exception  $e) {
		$alerta=[
			"tipo"=>"simple",
		"titulo"=>"Error",
		"texto"=>"Consulte con el administrador",
		"icono"=>"error"
		];	

		$log->guardarlog($e->getMessage());
		 
	}

		echo json_encode($alerta); 
	}