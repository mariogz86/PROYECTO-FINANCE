<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
//require_once "../autoload.php";
require_once '../PhpOffice/autoload.php';
 
 
use app\controllers\logerrorController;
$log = new logerrorController();
 
//incializar controlador y libreria en caso de utilizarse
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	use PhpOffice\PhpSpreadsheet\IOFactory;


	use app\controllers\hojaController;
	$inshoja = new hojaController();
	
	//Metodo GET para cargar las hojas asociadas al excel
	//Nota: si el excel esta demasiado cargado de hojas se dilata bastante en cargar
	//los datos comentariados dentro del metodo son ejemplos para usar la libreria PhpOffice\PhpSpreadsheet
	if(isset($_GET['cargarhojas']))
	{
		try {
		//validamos que el metodo envio algun valor seleccionado
		if($_GET['cargarhojas']!=""){
			//llamada al metodo del controlador
			$result =$inshoja->obtenerformulario($_GET['cargarhojas']);
			//resultado del formulario que se desea cargar en el metodo invocado
			if($result )
				{ 
					// $spreadsheet = new Spreadsheet();
					// $archivos = array();
					 $inputFileType = 'Xlsx'; 

					$rutaarchivo=$result[0]["ruta"]."/".$result[0]["nombre"];
					 
						
					//  } 
					 
					 /**
					  * Demostrar lectura de hoja de cálculo o archivo
					  * de Excel con PHPSpreadSheet: leer todo el contenido
					  * de un archivo de Excel
					   
					  */ 
					 # Recomiendo poner la ruta absoluta si no está junto al script
					 # Nota: no necesariamente tiene que tener la extensión XLSX
					 //$rutaArchivo = "LibroParaLeerConPHP.xlsx";
					 
					 
					 # Recuerda que un documento puede tener múltiples hojas
					 # obtener conteo e iterar
					 //$totalDeHojas = $documento->getSheetCount();


					  //para buscar
					//   $foundInCells = array();
					//   $searchValue = 'REM0001';
					//   $documento = \PhpOffice\PhpSpreadsheet\IOFactory::load($rutaarchivo);
					// 	  $worksheet = $documento->getSheetByName('Datos_Emp');
					// 	  foreach ($worksheet->getRowIterator() as $row) {
					// 		  $cellIterator = $row->getCellIterator();
					// 		  $cellIterator->setIterateOnlyExistingCells(true);
					// 		  foreach ($cellIterator as $cell) {
					// 			  if ($cell->getValue() == $searchValue) {
					// 				  $foundInCells[] =$cell->getCoordinate();
					// 			  }
					// 		  }
					// 	  }
					 
				   //fin de busqueda
			   
					 
					//  # Iterar hoja por hoja
					//  for ($indiceHoja = 0; $indiceHoja < $totalDeHojas; $indiceHoja++) {
					// 	 # Obtener hoja en el índice que vaya del ciclo
					// 	 $hojaActual = $documento->getSheet($indiceHoja);
					// 	 echo "<h3>Vamos en la hoja con índice $indiceHoja</h3>";
					 
					// 	 # Iterar filas
					// 	 foreach ($hojaActual->getRowIterator() as $fila) {
					// 		 foreach ($fila->getCellIterator() as $celda) {
					// 			 // Aquí podemos obtener varias cosas interesantes
					// 			 #https://phpoffice.github.io/PhpSpreadsheet/master/PhpOffice/PhpSpreadsheet/Cell/Cell.html
					 
					// 			 # El valor, así como está en el documento
					// 			 $valorRaw = $celda->getValue();
					 
					// 			 # Formateado por ejemplo como dinero o con decimales
					// 			 $valorFormateado = $celda->getFormattedValue();
					 
					// 			 # Si es una fórmula y necesitamos su valor, llamamos a:
					// 			 $valorCalculado = $celda->getCalculatedValue();
					 
					// 			 # Fila, que comienza en 1, luego 2 y así...
					// 			 $fila = $celda->getRow();
					// 			 # Columna, que es la A, B, C y así...
					// 			 $columna = $celda->getColumn();
					 
					// 			 echo "En <strong>$columna$fila</strong> tenemos el valor <strong>$valorRaw</strong>. ";
					// 			 echo "Formateado es: <strong>$valorFormateado</strong>. ";
					// 			 echo "Calculado es: <strong>$valorCalculado</strong><br><br>";
					// 		 }
					// 	 }
					//  }


					// // Retrieve the current active worksheet 
					// $sheet = $spreadsheet->getActiveSheet(); 
					
					// // Set the value of cell A1  
					// $sheet->setCellValue('A1', 'GeeksForGeeks!');
					// $writer = new Xlsx($spreadsheet);  
					// $writer->save('php://output');
					//$writer->save('gfg.xlsx'); 

					/** Create a new Reader of the type defined in $inputFileType **/
					$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
					/** Advise the Reader that we only want to load cell data **/
					$reader->setReadDataOnly(true);

					$worksheetData = $reader->listWorksheetInfo($rutaarchivo);

					foreach ($worksheetData as $worksheet) {

					$sheetName = $worksheet['worksheetName'];

					$archivos[] = $sheetName;

					}

					$res = array (
						'status' => 200,
						'message' => 'carga usuarios correcta',
						'data' => $archivos
							);
					echo json_encode($res);
				}  
			} else
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
				'message' =>  'Error consulte con el administrador'
			);
	
			$log->guardarlog($e->getMessage());
			echo json_encode($res);
		}
	}
	//Metodo GET para la carga del grid
	if(isset($_GET['cargagrid']))
	{
		try {
		//llamada al metodo del controlador
		$result =$inshoja->listarhoja();
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
	} catch (Exception  $e) {
		$res = array(
			'status' => 404,
			'message' =>  'Error consulte con el administrador'
		);

		$log->guardarlog($e->getMessage());
		echo json_encode($res);
	}  
		
	}
	//Metodo POST para el guardado del registro
	if(isset($_POST['modulo_Opcion'])=="registrar")
	{
		try {
		//llamada al metodo del controlador
		$validarduplicado =$inshoja->buscarhoja();
		//validacion en caso de que ya exista el registro
		if($validarduplicado){
			$alerta=[	
				"tipo"=>"simple",
				"titulo"=>"Advertencia",
				"texto"=>"Hoja de formulario ya existe en la base de datos.",
				"icono"=>"warning"
			];

		}else{//si no existe el registro se procede a guardar el registro
		//llamada al metodo en el controlador
		$result =$inshoja->guardar();
		//resultado al metodo invocado
		if($result){
			if ($_POST["idhoja"]=="0"){
			$alerta=[
				"tipo"=>"limpiar",
				"titulo"=>"Hoja de formulario",
				"texto"=>"El registro se guardo con éxito",
				"icono"=>"success"
			];}
			else{
				$alerta=[
					"tipo"=>"limpiar",
					"titulo"=>"Hoja de formulario",
					"texto"=>"El registro se actualizo con éxito",
					"icono"=>"success"
				];	
			}
		}else{
			if ($_POST["idcatalogo"]=="0"){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No se pudo registrar la Hoja de formulario, por favor intente nuevamente",
					"icono"=>"error"
				];
			}
				else{
					$alerta=[
						"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No se pudo actualizar la Hoja de formulario, por favor intente nuevamente",
					"icono"=>"error"
					];	
				}
			
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
	//Metodo POST para inactivar el estado de un registro
	if(isset($_POST['modulo_hoja']))
	{
		try {
		//llamada al metodo del controlador 1 para activar y 0 para inactivar el estado del registro
		if($_POST['modulo_hoja']=="activar"){
			$result =$inshoja->cambiarestado(1);
		}else{
			$result =$inshoja->cambiarestado(0);
		}
		
		//resultado que recive el metodo POST en la pantalla
		if($result){
			if($_POST['modulo_hoja']=="activar"){
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

 