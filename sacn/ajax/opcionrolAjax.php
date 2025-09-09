<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
require_once "../autoload.php";
 
 
 use app\controllers\logerrorController;
$log = new logerrorController();
	//incializando controlador
	use app\controllers\opcionrolController;
	$insopcion = new opcionrolController();

	//Metodo GET para la carga del grid en la pantalla
	if(isset($_GET['cargagrid']))
	{
		try {
		//llamada al metodo de carga del controlador
		$result =$insopcion->listar($_GET['cargagrid']);

		//resultado que se envia al metodo GET
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
    catch (Exception  $e) {
       
            $log->guardarlog($e->getMessage());	
            $res = array(
                'status' => 404,
                'message' =>  'No se encontro informacion'
            );
			echo json_encode($res); 
    }
		
	}

	//Metodo POST para el registro a guardar en la pantalla
	if(isset($_POST['modulo_Opcionrol']))
	{
		try {
        $result =$insopcion->Buscaropcion();
        if($result){
            $alerta=[
                "tipo"=>"limpiar",
                "titulo"=>"opcion rol",
                "texto"=>"Opción ya se encuentra asociada al rol.	",
                "icono"=>"warning"
            ];	

        }
        else
        {
		//se invoca el metodo de guardar del controlador
		$result =$insopcion->guardar();
		//resultado que se envia al metodo POST
		if($result){ 
			$alerta=[
				"tipo"=>"limpiar",
				"titulo"=>"opción de menú",
				"texto"=>"El registro se guardo con éxito",
				"icono"=>"success"
			];

			$opcionmenu =$insopcion->Buscaropcionmenu();
			$idmenu=$opcionmenu[0]["id_menu"];

			$existemenurol =$insopcion->Buscarrolmenu($idmenu, $_POST["cmb_rol"]	);

			if($existemenurol[0]["count"]==0)
			{
				$result =$insopcion->guardarrolmenu($idmenu, $_POST["cmb_rol"]	);

			}
			 
		}else{ 
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No se pudo registrar el opcion, por favor intente nuevamente",
					"icono"=>"error"
				];
		 
			
		}
    }

			}
				catch (Exception  $e) {
				
						$log->guardarlog($e->getMessage());	
						$alerta=[
								"tipo"=>"simple",
								"titulo"=>"Error",
								"texto"=>"Consulte con el administrador",
								"icono"=>"error"
							];
						
				}
		echo json_encode($alerta); 
	}

	//Metodo POST para activar e inactivar el estado del registro
	if(isset($_POST['modulo_opcion']))
	{
		try {
        if($_POST['modulo_opcion']=="inactivar"){
            $result =$insopcion->eliminar(0);
            if($result){
               
              

                $alerta=[
					"tipo"=>"limpiar",
					"titulo"=>"Eliminar registro",
					"texto"=>"El registro se elimino con éxito",
					"icono"=>"success"
				];
            }
		}else{
            $result =$insopcion->eliminar(1);
		 
		//resultado que recive el metodo POST en la pantalla
		if($result){
			 
				$alerta=[
					"tipo"=>"limpiar",
					"titulo"=>"Activar registro",
					"texto"=>"El registro se activo con éxito",
					"icono"=>"success"
				];
			} 
		 
    }
	}
				catch (Exception  $e) {
				
						$log->guardarlog($e->getMessage());	
						$alerta=[
								"tipo"=>"simple",
								"titulo"=>"Error",
								"texto"=>"Consulte con el administrador",
								"icono"=>"error"
							];
						
				}
		echo json_encode($alerta); 
	}