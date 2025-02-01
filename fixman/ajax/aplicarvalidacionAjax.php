<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
require_once "../autoload.php";
 
 
 
//incializar controladores
use app\controllers\aplicarvalidacionController;
$inscarga = new aplicarvalidacionController();

use app\controllers\FuncionesController;
$funciones = new FuncionesController();

 

        //Metodo POST para cargar  las boletas
        if(isset($_GET['cargarboletas']))
        {
            //se guardan los datos enviados del formulario
            $idformulario=$_GET['cargarboletas'];
             
            //llamada al metodo en el controlador para guardar o actualizar
            
            $result =$inscarga->obtenerboletas_por_idformulario($idformulario);
            
    
            
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


         //Metodo POST para cargar  las validaciones aplicadas al formulario
         if(isset($_GET['cargarvalidaciones']))
         {
             //se guardan los datos enviados del formulario
             $cargarvalidaciones=$_GET['cargarvalidaciones'];
              
             //llamada al metodo en el controlador para guardar o actualizar
             
             $result =$inscarga->obtenervalidacionesaplicadas_formulario($cargarvalidaciones);
             
     
             
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


           //Metodo GET para obtener los datos de las validaciones
           if(isset($_GET['obtenerdatosvalidacion']))
           {
               //se guardan los datos enviados del formulario
               $idvalidacion=$_GET['idvalidacion'];
               $boleta=$_GET['boleta'];
                
               //llamada al metodo en el controlador para guardar o actualizar
               
               $result =$inscarga->OBETNERDATOSVALIDACION($idvalidacion,$boleta);
               
       
               
              //resultado que se envia al metodo GET
                   if($result )
                   { 
                       $res = array (
                           'status' => 200,
                           'message' => 'carga datos correcta',
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


         //Metodo POST para el guardado de los registros
    if(isset($_POST['modulo_Opcion']))
	{
        if($_POST['modulo_Opcion']=="implementarvalidacion"){
            ini_set('max_execution_time', '0'); 
            ini_set('default_socket_timeout', 6000);
			 set_time_limit(0);
			 //ini_set('memory_limit', '1024'); 
            $validacionesnocumplen = array();
                $cmb_formulario=$_POST['cmb_formulario'];
                $cmb_boleta=$_POST['cmb_boleta'];

                if($cmb_boleta==0){
                    $validarboletas =$inscarga->obtenerboletas_por_idformulario($cmb_formulario);
                    $cantidadboletas =count($validarboletas);
                }else{
                    $validarboletas =$inscarga->obtenerboletas_por_boleta($cmb_boleta);
                    $cantidadboletas =count($validarboletas);
                }

                
        
                $formulario =$inscarga->listarvalidaciones_formulario($cmb_formulario);
                $validaciones=count($formulario);

                for ($colbol = 0; $colbol <= $cantidadboletas-1; ++$colbol) {
                   
                    $datoboleta=$validarboletas[$colbol];
                    for ($col = 0; $col <= $validaciones-1; ++$col) {

                        $value = $formulario[$col];
                        $tipovalidacion=$value['tipovalidacion'];
                        $condicion=$value['condicion'];
    
    
                        $parametros =$inscarga->obtenervalidacionparametros($cmb_formulario,$datoboleta['boleta'],$value['id_validacion']);
                        if(!empty($parametros)){
                                    $contparam=count($parametros); 
                                    $param1=0;
                                    $param2=0;
                                    $param3=0;
                                    for ($colpar = 0; $colpar <= $contparam-1; ++$colpar) {
                                        
                
                                            switch ($parametros[$colpar]['parametro']) {
                                                case "1":
                                                    $param1 =round( $inscarga->parseFloat(number_format($parametros[$colpar]['resultado'], 2, '.','')), 2, PHP_ROUND_HALF_UP);
                                                break;
                                                case "2":
                                                    $param2 =round( $inscarga->parseFloat(number_format($parametros[$colpar]['resultado'], 2, '.','')), 2, PHP_ROUND_HALF_UP);
                                                break;  
                                                case "3":
                                                    $param3 =round( $inscarga->parseFloat(number_format($parametros[$colpar]['resultado'], 2, '.','')), 2, PHP_ROUND_HALF_UP);
                                                break;   
                                                
                                            }
                                    }
    
                                $cumplevalidacion="NO";
                                switch ($tipovalidacion) {
                                    case "Validación_1":
                                        if((round($param1-$param2 , 2, PHP_ROUND_HALF_UP))==0){
                                            $cumplevalidacion="SI";
                                        }else{
                                            $validacionesnocumplen[] = array(
                                                'id_validacion'=>$value['id_validacion'],
                                                'id_formulario'=>$cmb_formulario,
                                                'validacion'=> $value['nombre'],                    
                                                'cumple'=> 'NO',                    
                                                'tipovalidacion'=>$tipovalidacion,
                                                'parametro1'=>$param1,
                                                'parametro2'=>$param2,
                                                'parametro3'=>'',
                                                'boleta'=>$datoboleta['boleta'],
                                            );
                                        }
                                        
                                    break;
                                    case "Validación_2":
                                        $resta=$param2-$param3;
                                        if((round($param1-$resta , 2, PHP_ROUND_HALF_UP))==0){
                                            $cumplevalidacion="SI";
                                        }else{
                                            $validacionesnocumplen[] = array(
                                                'id_validacion'=>$value['id_validacion'],
                                                'id_formulario'=>$cmb_formulario,
                                                'validacion'=> $value['nombre'],                    
                                                'cumple'=> 'NO',                    
                                                'tipovalidacion'=>$tipovalidacion,
                                                'parametro1'=>$param1,
                                                'parametro2'=>$param2,
                                                'parametro3'=>$param3,
                                                'boleta'=>$datoboleta['boleta'],
                                            );
                                        }
                                    break;  
                                    case "Validación_3":
                                        if($param1<=0 && $param2>0){
                                            $cumplevalidacion="SI";
                                        }else{
                                            $validacionesnocumplen[] = array(
                                                'id_validacion'=>$value['id_validacion'],
                                                'id_formulario'=>$cmb_formulario,
                                                'validacion'=> $value['nombre'],                    
                                                'cumple'=> 'NO',                    
                                                'tipovalidacion'=>$tipovalidacion,
                                                'parametro1'=>$param1,
                                                'parametro2'=>$param2,
                                                'parametro3'=>'',
                                                'boleta'=>$datoboleta['boleta'],
                                            );
                                        }
                                    break;   
                                    
                                } 
                            }
    
                    }
                } 
                if(!empty($validacionesnocumplen)){
                    $guardar =$inscarga->guardar(json_encode( $validacionesnocumplen) );
                    $validaciones =$inscarga->obtenervalidacionesaplicadas_formulario($cmb_formulario);
                    $alerta=[
                        "status"=>"200",
                        "tipo"=>"limpiar",
                        "titulo"=>"Implementar validacion",
                        "texto"=>"Existen validaciones que no cumplieron la condición parametrizada.", 
                        "icono"=>"warning",    
                        "datos"=>$validaciones                 
                    ];
                   
                }else{
                    $alerta=[
                        "status"=>"200",
                        "tipo"=>"limpiar",
                        "titulo"=>"Implementar validacion",
                        "texto"=>"Las validaciones se procesaron correctamente", 
                        "icono"=>"success",    
                        "datos"=>"[]"                 
                    ];
                }
        }else{
            $alerta=[
                "tipo"=>"limpiar",
                "titulo"=>"Implementar validacion",
                "texto"=>"No se encontro información.", 
                "icono"=>"warning",    
                "datos"=>"[]"                 
            ];
        }
        echo json_encode( $alerta); 
    }
    