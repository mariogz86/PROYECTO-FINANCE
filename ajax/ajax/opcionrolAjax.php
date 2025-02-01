<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
require_once "../autoload.php";
 
 
 
	//incializando controlador
	use app\controllers\opcionrolController;
	$insopcion = new opcionrolController();

	//Metodo GET para la carga del grid en la pantalla
	if(isset($_GET['cargagrid']))
	{
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

	//Metodo POST para el registro a guardar en la pantalla
	if(isset($_POST['modulo_Opcionrol']))
	{
        $result =$insopcion->Buscaropcion();
        if($result){
            $alerta=[
                "tipo"=>"limpiar",
                "titulo"=>"opcion de menú",
                "texto"=>"El nombre de la opcion ya existe en el sistema",
                "icono"=>"warning"
            ];	

        }
        else
        {
		//se invoca el metodo de guardar del controlador
		$result =$insopcion->guardar();
		//resultado que se envia al metodo POST
		if($result){
			if ($_POST["idopcion"]=="0"){
			$alerta=[
				"tipo"=>"limpiar",
				"titulo"=>"opción de menú",
				"texto"=>"El registro se guardo con éxito",
				"icono"=>"success"
			];}
			else{
				$alerta=[
					"tipo"=>"limpiar",
					"titulo"=>"opción de menú",
					"texto"=>"El registro se actualizo con éxito",
					"icono"=>"success"
				];	
			}
		}else{
			if ($_POST["idopcion"]=="0"){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No se pudo registrar el opcion, por favor intente nuevamente",
					"icono"=>"error"
				];
			}
				else{
					$alerta=[
						"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No se pudo actualizar el opcion, por favor intente nuevamente",
					"icono"=>"error"
					];	
				}
			
		}
    }


		echo json_encode($alerta); 
	}

	//Metodo POST para activar e inactivar el estado del registro
	if(isset($_POST['modulo_opcion']))
	{

        if($_POST['modulo_opcion']=="inactivar"){
            $result =$insopcion->cambiarestado(0);
            if($result){
               
              

                $alerta=[
					"tipo"=>"limpiar",
					"titulo"=>"Inactivar registro",
					"texto"=>"El registro se Inactivo con éxito",
					"icono"=>"success"
				];
            }
		}else{
            $result =$insopcion->cambiarestado(1);
		 
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
		echo json_encode($alerta); 
	}