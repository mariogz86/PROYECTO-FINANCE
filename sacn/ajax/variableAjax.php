<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
require_once "../autoload.php";
 
 
 
//incializar controladores
use app\controllers\variableController;
$insvariable = new variableController();

use app\controllers\FuncionesController;
$funciones = new FuncionesController();

use app\controllers\logerrorController;
$log = new logerrorController();


//Metodo GET para la carga del grid en la pantalla
if(isset($_GET['cargagrid']))
	{
        try {
		//llamada al metodo del controlador
		$result =$insvariable->listarvariable();
        //resultado que se envia al metodo invocado
		if($result )
		{ 
			$res = array (
				'status' => 200,
				'message' => 'carga usuarios correcta',
				'data' => $result,
                'coincidencia'=>$result[0]['idv_coincidencia'],
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
    //Metodo POST para el guardado de los registros
    if(isset($_POST['modulo_Opcion']))
	{
        try {
        //se guardan los datos enviados del formulario
        $archivos=$_POST['variables'];
        //llamada al metodo en el controlador para guardar o actualizar
        if($_POST['modulo_Opcion']=="registrar"){
            $result =$insvariable->guardar($archivos);
        }else{
            $result =$insvariable->actualizarvar($archivos);
        }

        
        //resultado que se envia al metodo invocado
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

    //Metodo GET que se utiliza para cargar las listas de hojas y archivos fuentes del formulario enviado
    if(isset($_GET['cargarhojas']))
	{
        try {
        //se valida si se envia algun valor en el metodo
            if($_GET['cargarhojas']!=""){
                //llamadas a funciones del controlador
                $datos=$funciones->ContarRegistros("select * from \"SACNSYS\".obtener_hoja where id_formulario=".$_GET['cargarhojas']." and u_estado=1");
                $contararchivos=$funciones->ContarRegistros("select * from \"SACNSYS\".obtener_archivofuente where id_formulario=".$_GET['cargarhojas']." and u_estado=1");
                //si existen hojas para el formulario
                if ($datos>0){
                    //llamada a la funcion del controlador para cargar las hojas
                    $result =$insvariable->obtenerhojas($_GET['cargarhojas']);

                    //en caso de que tenga archivos asociados el formulario
                    //se cargan tambien los archivos 
                    if($contararchivos>0){
                        //llamada al metodo del controlador para cargar los archivos del formulario
                        $result2 =$insvariable->obtenerarchivosfuentes($_GET['cargarhojas']);
                        //resultado que se envia al metodo invocado hojas + archivos
                        $res = array (
                            'status' => 200,
                            'message' => 'carga usuarios correcta',
                            'data' => $result,
                            'data2' => $result2,
                                );
                    } else{
                        //resultado que se envia al metodo invocado  Hojas
                        $res = array (
                            'status' => 200,
                            'message' => 'carga usuarios correcta',
                            'data' => $result, 
                                );
                    }

                    
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


     //Metodo GET para buscar variables nuevas para la hoja y archivo seleccionado del formulario
     if(isset($_GET['comparar']))
     {
        try {
         //llamada a al funcion del controlador
         $contararchivos=$funciones->ContarRegistros("select * from \"SACNSYS\".obtener_variable where id_formulario=".$_GET['formulario']." and id_hoja=".$_GET['hoja']." and id_archivofuente=".$_GET['archivo']."");
         $variables = array();
         //las clases que se utilizan para leer el archivo SAV se cargan con la clase autoload2.php
         //sino se invoca no se puede utilizar las clases
         require_once "../autoload2.php";
 
         //se prepara la consulta para cargar las variables que no se toman en cuenta para el archivo
         $consulta_datos="select Upper(m.nombre) as nombre
         from \"SACNSYS\".catalogovalor m
             inner join \"SACNSYS\".catalogo c on c.id_catalogo =m.id_catalogo 
             where c.codigo='excluvar' and c.u_estado='1' and m.u_estado='1'"; 
         //llamada a la funcion del controlador
         $exluirvariables = $funciones->ejecutarconsultaarreglo($consulta_datos); 
         
         //llamada a metodos del controlador para traer los datos del archivo y el formulario
         $archivo =$insvariable->obtenerarchivosfuente_por_id($_GET['archivo']);
         $formulario =$insvariable->obtenerformulario_porid($_GET['formulario']);
         //seteamos la ruta fisica donde estan ubicados el archivo y el formulario
         $rutaarchivo = $archivo[0]['ruta']."/".$archivo[0]["nombrearchivo"];
         $rutaform = $formulario[0]['ruta']."/".$formulario[0]["nombre"];
 
 
         //validar si existe el archivo en la ruta indicada
         if (!file_exists(trim($rutaarchivo))) { 
             $res = array (
                 'status' => 404,
                 "texto"=>"el archivo no existe en la ruta ".$rutaarchivo.", por favor validar.",
                 "tipo"=>"simple",
                 "titulo"=>"Error",
                 "icono"=>"error"
                 );
         }
         else{
            
             //si existe el archivo procedemos a leer sus datos con la libreria SACN/SAV
             $reader = \sacn\Sav\Reader::fromFile(trim($rutaarchivo))->read();
             
             
             
             //variable que contendra el filtro para las variables a excluir
             $buscarvar = array_column($reader->variables, 'name');;
 
             //en caso de que existan variables para el archivo y formulario y hoja
             if ($contararchivos>0){
                 $variablesBD =$insvariable->listarvariable();
                 $highestColumn =count($variablesBD);
 
                 //este ciclo sirve para encontrar variables que no esten en la base de datos para la combinacion formulario+hoja+archivo
                 //ademas de que no toma en cuenta las variables que estan en forma de excluir                
                 for ($col = 0; $col <= $highestColumn-1; ++$col) {
                     $value = $variablesBD[$col]['nombrevariable'];
 
                   
                     $found_key = array_search($value, $buscarvar);
 
                    
                         if(!is_numeric($found_key)){
                             $variables[] = array(
                                 'nombrevariable'=> $value,                    
                             );
                         }  
      
                 }
             } 
 
            
 
             //resultado que se envia al metodo invocado
             if(!empty($variables)){ 
                     $res = array (
                         'status' => 200,
                         'message' => 'carga usuarios correcta',
                         'data' => $variables,
                         'rutaformulario'=>$rutaform,
                             ); 
                 }
                 else{
                     $res = array (
                         'status' => 404,
                         "texto"=>"No se encontro información",
                         "tipo"=>"simple",
                         "titulo"=>"Advertencia",
                         "icono"=>"warning"
                         );
                 
                 }
 
                 }

                   }
    catch (Exception  $e) {
       
            $log->guardarlog($e->getMessage());	
        $res = array (
                         'status' => 404,
                         "texto"=>"Consulte con el administrador",
                         "tipo"=>"simple",
                         "titulo"=>"Advertencia",
                         "icono"=>"error"
                         );
    }
 
 
      
         echo json_encode($res); 
 
     }
  
    //Metodo GET para buscar variables nuevas para la hoja y archivo seleccionado del formulario
    if(isset($_GET['varnuevas']))
	{
        try {
        //llamada a al funcion del controlador
        $contararchivos=$funciones->ContarRegistros("select * from \"SACNSYS\".obtener_variable where id_formulario=".$_GET['formulario']." and id_hoja=".$_GET['hoja']." and id_archivofuente=".$_GET['archivo']."");
        $variables = array();
        //las clases que se utilizan para leer el archivo SAV se cargan con la clase autoload2.php
        //sino se invoca no se puede utilizar las clases
        require_once "../autoload2.php";

        //se prepara la consulta para cargar las variables que no se toman en cuenta para el archivo
        $consulta_datos="select Upper(m.nombre) as nombre
		from \"SACNSYS\".catalogovalor m
			inner join \"SACNSYS\".catalogo c on c.id_catalogo =m.id_catalogo 
			where c.codigo='excluvar' and c.u_estado='1' and m.u_estado='1'"; 
        //llamada a la funcion del controlador
        $exluirvariables = $funciones->ejecutarconsultaarreglo($consulta_datos); 
        
        //llamada a metodos del controlador para traer los datos del archivo y el formulario
        $archivo =$insvariable->obtenerarchivosfuente_por_id($_GET['archivo']);
        $formulario =$insvariable->obtenerformulario_porid($_GET['formulario']);
        //seteamos la ruta fisica donde estan ubicados el archivo y el formulario
        $rutaarchivo = $archivo[0]['ruta']."/".$archivo[0]["nombrearchivo"];
        $rutaform = $formulario[0]['ruta']."/".$formulario[0]["nombre"];


		//validar si existe el archivo en la ruta indicada
        if (!file_exists(trim($rutaarchivo))) { 
            $res = array (
                'status' => 404,
                "texto"=>"el archivo no existe en la ruta ".$rutaarchivo.", por favor validar.",
                "tipo"=>"simple",
				"titulo"=>"Error",
                "icono"=>"error"
                );
        }
        else{
           
			//si existe el archivo procedemos a leer sus datos con la libreria SACN/SAV
            $reader = \sacn\Sav\Reader::fromFile(trim($rutaarchivo))->read();
            
            $highestColumn = count($reader->variables); 
            
            //variable que contendra el filtro para las variables a excluir
            $buscarvar = array_column($exluirvariables, 'nombre');

            //en caso de que existan variables para el archivo y formulario y hoja
            if ($contararchivos>0){
                $variablesBD =$insvariable->listarvariable();
                $buscar = array_column($variablesBD, 'nombrevariable');

                //este ciclo sirve para encontrar variables que no esten en la base de datos para la combinacion formulario+hoja+archivo
                //ademas de que no toma en cuenta las variables que estan en forma de excluir                
                for ($col = 0; $col <= $highestColumn-1; ++$col) {
                    $value = $reader->variables[$col];

                  
                    $found_key = array_search($value->name, $buscar);

                  
                    $found_varexcluir = array_search(strtoupper($value->name),$buscarvar);
                    if(!is_numeric($found_varexcluir)){

                        if(!is_numeric($found_key)){
                            $variables[] = array(
                                'nombrevariable'=> $value->name,                    
                            );
                        } 
                   }
     
                }
            }else{
                //sino existen registros en la base de datos 
                //se cargaran todas las variables sin meter las excluidas
                for ($col = 0; $col <= $highestColumn-1; ++$col) {
                    $value = $reader->variables[$col];
                    $found_varexcluir = array_search(strtoupper($value->name),$buscarvar);
                    if(!is_numeric($found_varexcluir)){
                    $variables[] = array(
                        'nombrevariable'=> $value->name,                    
                    );
                }
                     // 
                }
            }

           

            //resultado que se envia al metodo invocado
            if(!empty($variables)){ 
                    $res = array (
                        'status' => 200,
                        'message' => 'carga usuarios correcta',
                        'data' => $variables,
                        'rutaformulario'=>$rutaform,
                            ); 
                }
                else{
                    $res = array (
                        'status' => 404,
                        "texto"=>"No se encontro información",
                        "tipo"=>"simple",
                        "titulo"=>"Advertencia",
                        "icono"=>"warning"
                        );
                
                }

                }

                              }
    catch (Exception  $e) {
       
            $log->guardarlog($e->getMessage());	
           $res = array (
                         'status' => 404,
                         "texto"=>"Consulte con el administrador",
                         "tipo"=>"simple",
                         "titulo"=>"Advertencia",
                         "icono"=>"error"
                         );
    }
 

     
        echo json_encode($res); 

    }
    //Metodo GET para reclasificar las variables
    if(isset($_GET['reclasificar']))
	{
        try {
        //llamada a la funcion del controlador
        $contararchivos=$funciones->ContarRegistros("select * from \"SACNSYS\".obtener_variable where id_formulario=".$_GET['formulario']." and id_hoja=".$_GET['hoja']." and id_archivofuente=".$_GET['archivo']."");
 
        
         //llamada a metodos del controlador para traer los datos del archivo y el formulario
        $archivo =$insvariable->obtenerarchivosfuente_por_id($_GET['archivo']);
        $formulario =$insvariable->obtenerformulario_porid($_GET['formulario']);
           //seteamos la ruta fisica donde estan ubicados el archivo y el formulario
        $rutaarchivo = $archivo[0]['ruta']."/".$archivo[0]["nombrearchivo"];
        $rutaform = $formulario[0]['ruta']."/".$formulario[0]["nombre"];


		//validamos que el archivo exista
        if (!file_exists(trim($rutaarchivo))) { 
            $res = array (
                'status' => 404,
                "texto"=>"el archivo no existe en la ruta ".$rutaarchivo.", por favor validar.",
                "tipo"=>"simple",
				"titulo"=>"Error",
                "icono"=>"error"
                );
        }
        else{
            //si existen datos en la base de datos se llenan las variables 
            if ($contararchivos>0){
                $variablesBD =$insvariable->listarvariable();
 
            }  
            //resultado que se envia al metodo invocado
            if(!empty($variablesBD)){ 
                    $res = array (
                        'status' => 200,
                        'message' => 'carga usuarios correcta',
                        'data' => $variablesBD,
                        'rutaformulario'=>$rutaform,
                            ); 
                }
                else{
                    $res = array (
                        'status' => 404,
                        "texto"=>"No se encontro información",
                        "tipo"=>"simple",
                        "titulo"=>"Advertencia",
                        "icono"=>"warning"
                        );
                
                }

                }
              }
    catch (Exception  $e) {
       
            $log->guardarlog($e->getMessage());	
           $res = array (
                         'status' => 404,
                         "texto"=>"Consulte con el administrador",
                         "tipo"=>"simple",
                         "titulo"=>"Advertencia",
                         "icono"=>"error"
                         );
    }
 
     
        echo json_encode($res); 

    }
    //Metodo POST para activar e inactivar el estado de un registro
    if(isset($_POST['modulo_variable']))
	{
        try {
        //llamada al metodo del controlador 1= Activar 0=Inactivar
		if($_POST['modulo_variable']=="activar"){
			$result =$insvariable->cambiarestado(1);
		}else{
			$result =$insvariable->cambiarestado(0);
		}
		
        //resultado que se envia al metodo invocado
		if($result){
			if($_POST['modulo_variable']=="activar"){
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