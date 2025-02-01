<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
require_once "../autoload.php";
 
 
 
	//incializando controlador
	use app\controllers\rolController;
	$insrol = new rolController();

	//Metodo GET para la carga del grid en la pantalla
	if(isset($_GET['cargagrid']))
	{
		//llamada al metodo de carga del controlador
		$result =$insrol->listarrol();

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
	if(isset($_POST['modulo_Opcion']))
	{
        $result =$insrol->BuscarRol();
        if($result){
            $alerta=[
                "tipo"=>"limpiar",
                "titulo"=>"Rol duplicado",
                "texto"=>"El nombre del rol ya existe en el sistema",
                "icono"=>"warning"
            ];	

        }
        else
        {
		//se invoca el metodo de guardar del controlador
		$result =$insrol->guardar();
		//resultado que se envia al metodo POST
		if($result){
			if ($_POST["idrol"]=="0"){
			$alerta=[
				"tipo"=>"limpiar",
				"titulo"=>"Rol registrado",
				"texto"=>"El registro se guardo con éxito",
				"icono"=>"success"
			];}
			else{
				$alerta=[
					"tipo"=>"limpiar",
					"titulo"=>"Rol actualizado",
					"texto"=>"El registro se actualizo con éxito",
					"icono"=>"success"
				];	
			}
		}else{
			if ($_POST["idrol"]=="0"){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No se pudo registrar el rol, por favor intente nuevamente",
					"icono"=>"error"
				];
			}
				else{
					$alerta=[
						"tipo"=>"simple",
					"titulo"=>"Error",
					"texto"=>"No se pudo actualizar el rol, por favor intente nuevamente",
					"icono"=>"error"
					];	
				}
			
		}
    }


		echo json_encode($alerta); 
	}

	//Metodo POST para activar e inactivar el estado del registro
	if(isset($_POST['modulo_rol']))
	{

        if($_POST['modulo_rol']=="inactivar"){
            $result =$insrol->validar_inactivar();
            if($result){
                $alerta=[
                    "tipo"=>"limpiar",
                    "titulo"=>"Inactivar",
                    "texto"=>"El rol no se puede inactivar está asignado a uno o más usuarios.",
                    "icono"=>"warning"
                ];	
    
            }else{
                $result =$insrol->cambiarestado(0);

                $alerta=[
					"tipo"=>"limpiar",
					"titulo"=>"Inactivar registro",
					"texto"=>"El registro se Inactivo con éxito",
					"icono"=>"success"
				];
            }
		}else{

		//llamada al metodo del controlador 1 para activar y 0 para inactivar el estado del registro
		if($_POST['modulo_rol']=="activar"){
			$result =$insrol->cambiarestado(1);
		}
		
		//resultado que recive el metodo POST en la pantalla
		if($result){
			if($_POST['modulo_rol']=="activar"){
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
    }
		echo json_encode($alerta); 
	}