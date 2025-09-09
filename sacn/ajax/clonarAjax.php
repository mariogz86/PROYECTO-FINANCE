<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
require_once "../autoload.php";

 

	//incializando controlador
	use app\controllers\clonarController;
	$insclonar = new clonarController();

	use app\controllers\logerrorController;
	$log = new logerrorController();
 

    //Metodo GET para el guardado de un registro
	if(isset($_GET['modulo_Opcion'])=="clonarformulario")
	{
		try {
		
		$cmb_formulario = $_POST["cmb_formulario"];  
		$cmb_anio = $_POST["cmb_anio"];  
					
		$nombre = $_POST["nombre"]; 
		$anio = $_POST["anio"]; 

	//validar si el formulario que se quiere guardar ya existe
		$formulario =$insclonar->obtenerformulario($cmb_formulario);

		 $cmb_tipocarga = $formulario[0]["id_vtipocarga"];
		 $cmb_catatividad = $formulario[0]["cod_actividad"];
		 $cmb_detactiv = $formulario[0]["id_vdetactividad"];
		 $cmb_tipoencuesta = $formulario[0]["id_vtipoencuesta"];
		 $ruta = trim($formulario[0]["ruta"]);

		 //validar si el formulario que se quiere guardar ya existe
		 $validarduplicado =$insclonar->buscarformulario($cmb_anio,$cmb_tipocarga,$cmb_catatividad,$cmb_detactiv,$cmb_tipoencuesta,$nombre);

			//mensaje que se envia si el registro ya existe
			if (!empty($validarduplicado)){
				$alerta=[	
					"tipo"=>"simple",
					"titulo"=>"Advertencia",
					"texto"=>"Los datos del formulario ya existen en la base de datos.",
					"icono"=>"warning"
				];
			}else{
				//guardamos el formulario	
				$idformulario =$insclonar->guardarformulario($cmb_anio,$cmb_tipocarga,$cmb_catatividad,$cmb_detactiv,$cmb_tipoencuesta,$nombre,$ruta);

				//guardamos las hojas para el formulario
				$clonarhoja = $insclonar->clonar($cmb_formulario,$idformulario,trim($anio));
				if($clonarhoja){
				$alerta=[
					"tipo"=>"limpiar",
					"titulo"=>"Clonar Configuración",
					"texto"=>"El proceso se realizo con éxito",
					"icono"=>"success"
				];
				}else{
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Error",
						"texto"=>"No se pudo registrar el Formulario, por favor intente nuevamente",
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

	if(isset($_POST['borrarformato'])=="borrar")
	{

		$formulario = $_POST["formulario"]; 

		

		try
            {
				$result =$insclonar->borrarformulario($formulario);	
				$res = array (
					'status' => 200,
					'message' =>  'No se encontro informacion'
					);
            }
			catch (Exception  $e) {
				$res = array (
					'status' => 404,
					'message' =>  'No se encontro informacion'
					);

					$log->guardarlog($e->getMessage());	
 
			}
			 
			echo json_encode($res); 
	}