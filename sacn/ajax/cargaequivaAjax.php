<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
//require_once "../autoload.php";
require_once '../PhpOffice/autoload.php';

//incializar controlador y libreria en caso de utilizarse
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

use app\controllers\logerrorController;
$log = new logerrorController();


//incializar controladores
use app\controllers\cargaequivaController;

$insvalidacion = new cargaequivaController();

use app\controllers\FuncionesController;

$funciones = new FuncionesController();
//Metodo GET para la carga del grid en la pantalla
if (isset($_GET['cargagrid'])) {
	try{
	//llamada al metodo del controlador
	$result = $insvalidacion->listar();
	//resultado que se envia al metodo invocado
	if ($result) {
		$res = array(
			'status' => 200,
			'message' => 'carga usuarios correcta',
			'data' => $result,
		);
		echo json_encode($res);
	} else {
		$res = array(
			'status' => 404,
			'message' =>  'No se encontro informacion'
		);
		echo json_encode($res);
	}
	  }
    catch (Exception  $e) {
       
            $log->guardarlog($e->getMessage());	
            $res = array(
                'status' => 404,
                'message' =>  'No se encontro informacion'
            );
			echo json_encode($res);
    }
}

//Metodo GET para la carga del grid de parametros
if (isset($_GET['cargagridparametro'])) {
	try {
	//llamada al metodo del controlador
	$result = $insvalidacion->listarparametros();
	//resultado que se envia al metodo invocado
	if ($result) {
		$res = array(
			'status' => 200,
			'message' => 'carga usuarios correcta',
			'data' => $result,
		);
		echo json_encode($res);
	} else {
		$res = array(
			'status' => 404,
			'message' =>  'No se encontro informacion'
		);
		echo json_encode($res);
	}
	 }
    catch (Exception  $e) {
       
            $log->guardarlog($e->getMessage());	
            $res = array(
                'status' => 404,
                'message' =>  'No se encontro informacion'
            );
			echo json_encode($res);
    }
}

//Metodo GET para guardar registro
if (isset($_GET['modulo_Opcion'])) {
try {
	if ($_GET['modulo_Opcion'] == "registrar") {
		$existe = $insvalidacion->buscarvalidacion();


		if ($existe > 0) {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Advertencia",
				"texto" => "El nombre de la validación ya existe en la base de datos.",
				"icono" => "warning"
			];
		} else {


			//se invoca el metodo de guardar del controlador
			$result = $insvalidacion->guardar();
			//resultado que se envia al metodo POST
			if ($result > 0) {
				if ($_POST["idvalidacion"] == "0") {
					$alerta = [
						"tipo" => "limpiar",
						"titulo" => "Validación",
						"texto" => "El registro se guardo con éxito",
						"icono" => "success",
						"cargargrid" => "0",
						"idvalidacion" => $result,
					];
				} else {
					$alerta = [
						"tipo" => "limpiar",
						"titulo" => "Validación",
						"texto" => "El registro se actualizo con éxito",
						"icono" => "success",
						"cargargrid" => "0",
						"idvalidacion" => $result,
					];
				}
			} else {
				if ($_POST["idvalidacion"] == "0") {
					$alerta = [
						"tipo" => "simple",
						"titulo" => "Error",
						"texto" => "No se pudo registrar la validación, por favor intente nuevamente",
						"icono" => "error"
					];
				} else {
					$alerta = [
						"tipo" => "simple",
						"titulo" => "Error",
						"texto" => "No se pudo actualizar la validación, por favor intente nuevamente",
						"icono" => "error"
					];
				}
			}
		}
	} else {
		//se invoca el metodo de guardar del controlador
		$result = $insvalidacion->guardarparametro();
		//resultado que se envia al metodo POST
		if ($result > 0) {
			$alerta = [
				"tipo" => "limpiar",
				"titulo" => "Validación",
				"texto" => "El registro se guardo con éxito",
				"icono" => "success",
				"cargargrid" => "0",
				"idvalidacion" => $_POST["idvalidacionparametro"],
			];
		} else {
			$alerta = [
				"tipo" => "simple",
				"titulo" => "Error",
				"texto" => "No se pudo registrar el parametro, por favor intente nuevamente",
				"icono" => "error"
			];
		}
	}
	 }
    catch (Exception  $e) {
       
            $log->guardarlog($e->getMessage());	
            $alerta = [
				"tipo" => "simple",
				"titulo" => "Error",
				"texto" => "Consulte con el administrador",
				"icono" => "error"
			];
    }

	echo json_encode($alerta);
}

//Metodo GET para obtener las variables de un archivo
if (isset($_GET['cargarvariables'])) {
	try {
	//llamada al metodo del controlador
	$result = $insvalidacion->listarvariable();
	//resultado que se envia al metodo invocado
	if ($result) {
		$res = array(
			'status' => 200,
			'message' => 'carga de datos correcto',
			'data' => $result,
		);
		echo json_encode($res);
	} else {
		$res = array(
			'status' => 404,
			'message' =>  'No se encontro informacion'
		);
		echo json_encode($res);
	}
	 }
    catch (Exception  $e) {
       
            $log->guardarlog($e->getMessage());	
            $res = array(
			'status' => 404,
			'message' =>  'No se encontro informacion'
		);
		echo json_encode($res);
    }
}

//Metodo POST para activar e inactivar el estado del registro
if (isset($_POST['accion'])) {
	try {
	//llamada al metodo del controlador 1 para activar y 0 para inactivar el estado del registro
	if ($_POST['accion'] == "activar") {
		$result = $insvalidacion->cambiarestado(1);
	} else {
		$result = $insvalidacion->cambiarestado(0);
	}

	//resultado que recive el metodo POST en la pantalla
	if ($result) {
		if ($_POST['accion'] == "activar") {
			$alerta = [
				"tipo" => "limpiar",
				"titulo" => "Activar registro",
				"texto" => "El registro se activo con éxito",
				"cargargrid" => "1",
				"icono" => "success"
			];
		} else {
			$alerta = [
				"tipo" => "limpiar",
				"titulo" => "Inactivar registro",
				"texto" => "El registro se Inactivo con éxito",
				"cargargrid" => "1",
				"icono" => "success"
			];
		}
	} else {
		$alerta = [
			"tipo" => "simple",
			"titulo" => "Error",
			"texto" => "No se pudo realizar la acción solicitada, por favor intente nuevamente",
			"icono" => "error"
		];
	}
	 }
    catch (Exception  $e) {
       
            $log->guardarlog($e->getMessage());	
           $alerta = [
			"tipo" => "simple",
			"titulo" => "Error",
			"texto" => "Consulte con el administrador",
			"icono" => "error"
		];
    }
	echo json_encode($alerta);
}

//Metodo POST para eliminar un parametro de validacion
if (isset($_POST['accioneliminar'])) {
	//llamada al metodo del controlador 1 para activar y 0 para inactivar el estado del registro
try {
	$result = $insvalidacion->eliminar();


	//resultado que recive el metodo POST en la pantalla
	if ($result) {
		$alerta = [
			"tipo" => "limpiar",
			"titulo" => "Eliminar registro",
			"texto" => "El registro se activo con éxito",
			"cargargrid" => "0",
			"idvalidacion" => $_POST["id_validacion"],
			"icono" => "success"
		];
	} else {
		$alerta = [
			"tipo" => "simple",
			"titulo" => "Error",
			"texto" => "No se pudo realizar la acción solicitada, por favor intente nuevamente",
			"icono" => "error"
		];
	}
 }
    catch (Exception  $e) {
       
            $log->guardarlog($e->getMessage());	
           $alerta = [
			"tipo" => "simple",
			"titulo" => "Error",
			"texto" => "Consulte con el administrador",
			"icono" => "error"
		];
    }
	echo json_encode($alerta);
}


//Metodo POST para el guardado de los registros
if (isset($_POST['guardarcarga'])) {
	try {
	//se guardan los datos enviados del formulario
	$parametros = $_POST['validaciones']; 
	//$formulario = $_POST['formulario']; 
	//llamada al metodo en el controlador para guardar o actualizar
	ini_set('max_execution_time', '0');
	set_time_limit(0);
	//$insvalidacion->limpiarvalidaciones($formulario, $anio);
	//$result = $insvalidacion->guardarvalidaciones($validaciones);
	//resultado que se envia al metodo invocado
	$result = $insvalidacion->guardarcargaequivalencias($parametros);
	if ($result) { 
			$res = array(
				'status' => 200,

			);
		echo json_encode($res);
	} else {
		$res = array(
			'status' => 404,
			'message' =>  'No se encontro informacion'
		);
		echo json_encode($res);
	}
	 }
    catch (Exception  $e) {
       
            $log->guardarlog($e->getMessage());	
          $res = array(
			'status' => 404,
			'message' =>  'No se encontro informacion'
		);
		echo json_encode($res);
    }
}

//Metodo POST para cambio de ruta
if (isset($_POST['hdf_cambiornombre']) == "cambio") {
try {
	$result = $insvalidacion->guardar_cambionombre();
	//resultado que se envia al guardar el registro
	if ($result) {
		$alerta = [
			"tipo" => "limpiar",
			"titulo" => "Cambio de ruta",
			"texto" => "Nombre actualizado para los registros seleccionados",
			"icono" => "success"
		];
	} else {
		$alerta = [
			"tipo" => "simple",
			"titulo" => "Error",
			"texto" => "No se pudo actualizar, por favor intente nuevamente",
			"icono" => "error"
		];
	}
	 }
    catch (Exception  $e) {
       
            $log->guardarlog($e->getMessage());	
          $alerta = [
			"tipo" => "simple",
			"titulo" => "Error",
			"texto" => "Consulte con el administrador",
			"icono" => "error"
		];
    }
	echo json_encode($alerta);
}


if (isset($_GET['guardarcarga'])) {

try {

	$arr_file = explode('.', $_FILES['file']['name']);
	$extension = end($arr_file);
	if ('csv' == $extension) {
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
	} else {
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
	}
	$spreadsheet = $reader->load($_FILES['file']['tmp_name']);




	// Obtener los datos de la hoja "validaciones"
	$validacionesSheet = $spreadsheet->getSheetByName('validaciones');
	$validacionesData = [];
	foreach ($validacionesSheet->getRowIterator() as $row) {
		$rowData = [];
		$col = 1;
		$rowIndex = $row->getRowIndex();
		$rowIndex = $row->getRowIndex();
		if ($rowIndex > 1) {

			$hasData = false;
			foreach ($row->getCellIterator() as $cell) {
				$value = $cell->getValue();

				// Si la celda tiene datos, la agregamos a la fila y marcamos que hay datos
				if (!empty($value)) {
					$hasData = true;
				}

				$rowData[$validacionesSheet->getCellByColumnAndRow($col, 1)->getValue()] = $cell->getValue();
				$col = $col + 1;
			}
			// Solo agregamos la fila si tiene datos
			if ($hasData) {
				$validacionesData[] = $rowData;
			}
		}
	}

	// Obtener los datos de la hoja "parametros"
	$parametrosSheet = $spreadsheet->getSheetByName('parametros');
	$parametrosData = [];
	foreach ($parametrosSheet->getRowIterator() as $row) {
		$rowData = [];
		$col = 1;
		$rowIndex = $row->getRowIndex();
		if ($rowIndex > 1) {
			$hasData = false;
			foreach ($row->getCellIterator() as $cell) {
				$value = $cell->getValue();

				// Si la celda tiene datos, la agregamos a la fila y marcamos que hay datos
				if (!empty($value)) {
					$hasData = true;
				}
				$rowData[$parametrosSheet->getCellByColumnAndRow($col, 1)->getValue()] = $cell->getValue();
				$col = $col + 1;
			}
			if ($hasData) {
			$parametrosData[] = $rowData;
			}
		}
	}



	//se guardan los datos enviados del formulario
	$validaciones = json_encode($validacionesData);
	$parametros = json_encode($parametrosData);
	$formulario = $validacionesSheet->getCellByColumnAndRow(3, 2)->getValue();
	$anio = $validacionesSheet->getCellByColumnAndRow(2, 2)->getValue();
	//llamada al metodo en el controlador para guardar o actualizar
	ini_set('max_execution_time', '0');
	set_time_limit(0);
	$insvalidacion->limpiarvalidaciones($formulario, $anio);
	$result = $insvalidacion->guardarvalidaciones($validaciones);
	//resultado que se envia al metodo invocado
	if ($result) {

		$validaparametro = $insvalidacion->validarcargaparametros($parametros);
		if (!empty($validaparametro)) {
			 
			$alerta = [
				"tipo" => "limpiar",
				"titulo" => "Carga excel",
				"texto" => "Carga con error en parametros, por favor validar",
				"icono" => "warning",
				'data' => $validaparametro,
			];
		} else {
			ini_set('max_execution_time', '0');
			set_time_limit(0);
			$result = $insvalidacion->guardarparametrosexcel($parametros);
			$alerta = [
				"tipo" => "limpiar",
				"titulo" => "Carga excel",
				"texto" => "Carga realizada con éxito",
				"icono" => "success"
			];
		}


		echo json_encode($alerta);
	} else {
		$alerta = [
			"tipo" => "limpiar",
			"titulo" => "Cambio de ruta",
			"texto" => "Nombre actualizado para los registros seleccionados",
			"icono" => "success"
		];
		echo json_encode($alerta);
	}
	 }
    catch (Exception  $e) {
       
            $log->guardarlog($e->getMessage());	
          $alerta = [
			"tipo" => "simple",
			"titulo" => "Error",
			"texto" => "Consulte con el administrador",
			"icono" => "error"
		];
		echo json_encode($alerta);
    }
}
