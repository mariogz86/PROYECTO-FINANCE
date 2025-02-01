<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
require_once "../autoload.php";
 
 
 
//incializar controladores
use app\controllers\cargaController;
$inscarga = new cargaController();

use app\controllers\FuncionesController;
$funciones = new FuncionesController();

    //Metodo POST para el guardado de los registros
    if(isset($_POST['modulo_Opcion']))
	{
        ini_set('max_execution_time', '0'); 
        set_time_limit(0);
        //ini_set('memory_limit', '1024'); 
        //las clases que se utilizan para leer el archivo SAV se cargan con la clase autoload2.php
        //sino se invoca no se puede utilizar las clases
        require_once "../autoload2.php";
        //se guardan los datos enviados del formulario
        $cmb_formulario=$_POST['cmb_formulario'];
        $cmb_archivofuente=$_POST['cmb_archivofuente'];

        if ( $cmb_archivofuente==0){
            $formulario =$inscarga->obtenerarchivosfuente_por_idformulario($cmb_formulario);
        }else{
            $formulario =$inscarga->obtenerarchivosfuentes_poridarchivo($cmb_archivofuente);

        }


       

        //obtenemos los archivos a buscar informacion
        

        //validamos que esten los archivos en la ruta indicada 
        //de lo contrario indcarle al usuario que faltan archivos para cargar al formulario
        $totalregistro =count($formulario);
        for ($col = 0; $col <= $totalregistro-1; ++$col) {
        $rutaarchivo = $formulario[$col]['ruta']."/".$formulario[$col]["nombrearchivo"];

            //validar si existe el archivo en la ruta indicada
            if (!file_exists(trim($rutaarchivo))) { 
                $archivosnoencontrados[] = array(
                                'nombrearchivo'=> $formulario[$col]["nombrearchivo"],                    
                                'ruta'=> $formulario[$col]["ruta"],                    
                            );
            }
        }

        if(!empty($archivosnoencontrados)){
            $alerta=[
                "tipo"=>"limpiar",
                "titulo"=>"Carga de archivos",
                "texto"=>"Archivos no encontrados en las rutas indicadas, favor de validar",
                "Erroarchivos"=>"1", 
                "icono"=>"warning",
                "datos"=>$archivosnoencontrados
            ];
            echo json_encode( $alerta); 
            }else{
                //proceso de carga de datos segun los archivos

                $finarrelgo = array();
                $datosarchivos = array();
                for ($colum = 0; $colum <= $totalregistro-1; ++$colum) {
                    $rutaarchivo = $formulario[$colum]['ruta']."/".$formulario[$colum]["nombrearchivo"];


                     //se prepara la consulta para buscar boletas para el a
                    $consulta_datos="select distinct boleta from \"SYSTEM\".VALORVARIABLE where id_formulario=".$cmb_formulario." and id_archivofuente=".$formulario[$colum]['id_archivofuente'].""; 
                    //llamada a la funcion del controlador
                    $boletas = $funciones->ejecutarconsultaarreglo($consulta_datos); 
                    $buscarvar = array_column($boletas, 'boleta');
            
                         //si existe el archivo procedemos a leer sus datos con la libreria SACN/SAV
                        $reader = \sacn\Sav\Reader::fromFile(trim($rutaarchivo))->read();
                        
                        $highestColumn = count($reader->variables); 
                        $highestRow=count($reader->data);

                       
                        for ($row = 0; $row <= $highestRow-1; ++$row) {
                            $value = $reader->data[$row];
                           
                            $variables = array();
                            $variables['archivo'] = $formulario[$colum]["nombrearchivo"];
                            $variables['id_archivofuente'] = $formulario[$colum]["id_archivofuente"];
                            $variables['id_formulario'] = $formulario[$colum]["id_formulario"];
                            
                             for ($col = 0; $col <= $highestColumn-1; ++$col) {
                                $datovar = $reader->variables[$col];
                                    if (strval($value[$col])=="-1.7976931348623E+308"){
                                        $variables[$datovar->name] = 0;
                                    }
                                else{ 
                                    $variables[$datovar->name] =trim(str_ireplace("\"", "", str_ireplace("'", "", $value[$col])));
                                 
                                }

                                if($datovar->name=="BOLETA"){
                                    $found_key = array_search($value[$col], $buscarvar);
                                    if(is_numeric($found_key)){
                                        $variables["existe"]="SI";
                                }else{
                                    $variables["existe"]="NO";
                                }
                            }
                                    
                             }
                             array_push($datosarchivos, $variables);
                             
                         }

                       //  array_push($finarrelgo, $datosarchivos);
                    }



                $alerta=[
                    "tipo"=>"limpiar",
                    "titulo"=>"Carga de datos",
                    "texto"=>"Todos los archivos se procesaron correctamente.",
                    "Erroarchivos"=>"0", 
                    "icono"=>"success",    
                    "datos"=>$datosarchivos                 
                ];
                echo json_encode( $alerta); 

            }
                

        //llamada al metodo en el controlador para guardar o actualizar
        // if($_POST['modulo_Opcion']=="registrar"){
        //     $result =$inscarga->guardar($archivos);
        // }else{
        //     $result =$inscarga->actualizarvar($archivos);
        // }

        
        // //resultado que se envia al metodo invocado
        // if($result )
		// { 
		// 	$res = array (
        //         'status' => 200, 
		// 			);
		// 	echo json_encode($res);
		// }
		// else
		// {
		// 	$res = array (
		// 		'status' => 404,
		// 		'message' =>  'No se encontro informacion'
		// 		);
		// 	echo json_encode($res); 
		// } 
        
    }


        //Metodo POST para el guardado de los registros
        if(isset($_POST['Cargaguardar'])=="guardar")
        {
            //se guardan los datos enviados del formulario
            $archivos=$_POST['datos'];
            $nuevas=$_POST['nuevas'];
            //llamada al metodo en el controlador para guardar o actualizar
            
            $result =$inscarga->guardar($archivos,$nuevas);
            
    
            
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
                if(empty($result)){
                    $res = array (
                        'status' => 200,
                        'message' =>  'No se encontro informacion'
                        );
                }else{
                    $res = array (
                        'status' => 404,
                        'message' =>  'No se encontro informacion'
                        );
                }
                
                echo json_encode($res); 
            } 
            
        }
    