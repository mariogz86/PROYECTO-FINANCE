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
    use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
 
 
 
//incializar controladores
use app\controllers\generarController;
$inscarga = new generarController();

use app\controllers\FuncionesController;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Trim;

$funciones = new FuncionesController();

 

        //Metodo POST para cargar  las boletas
        if(isset($_GET['cargarboletas']))
        {
            try  {
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
        if(isset($_POST['modulo_Opcion'])=="generarplantilla")
        {
            try {
            $boleta=$_POST["cmb_boleta"];
            $formulario =$inscarga->obtenerdatos_formulario($_POST["cmb_formulario"]);
            if (!is_dir( Trim($_POST["ruta"]))) {
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Error",
                    "texto"=>"Ruta indicada no existe, por favor validar.",
                    "icono"=>"error"
                ];
            }else{
                ini_set('max_execution_time', '0'); 
                set_time_limit(0);
                ini_set('memory_limit', '1024M'); 
                //ini_set('memory_limit', '1024'); 
                $result =$inscarga->obtenervalorboleta_formulario($_POST["cmb_boleta"],$formulario[0]["nombre"],$formulario[0]["anio"]);

                $inputFileType = 'Xlsx'; 

                $rutaarchivo=$formulario[0]["ruta"]."/".$formulario[0]["nombre"];

                //datos de la boleta para el formulario


					//ejemplo para escribir en el excel
					// $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
					// $reader->setLoadSheetsOnly(["Datos_Emp", "Datos","AnexosEmpresa"]);
					// $spreadsheet = $reader->load($rutaarchivo);


                    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($rutaarchivo);
					 

                     $archivo2 = "";
                     $variable2 = "";
                     $varhoja = "";
                     $fila = 0;
                     $totalfilas=count($result);

                     for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) {
                     $hoja=(string)$result[$colbol]['hoja'];
                     $celda=(string)$result[$colbol]['posicion'];
                     $valor =(string)$result[$colbol]['valor'];
                     $archivo =(string)$result[$colbol]['archivo'];
                     $variable =(string)$result[$colbol]['nombrevariable'];
					

                     if ($celda <> "") {
					     $numeroposicion = $inscarga->limpiarletras($celda);
                         $letras = $inscarga->limpiarnumeros($celda);
					     
                         if ($hoja <> $varhoja){ 
                            $sheet =$spreadsheet->getSheetByName($hoja);
                         }

                       
                                 
                         if (($archivo == $archivo2) && ($variable == $variable2)) { 
                                    $fila = $fila + 1 ;
                                    $numeroposicion = $numeroposicion + $fila;
                                    $celda2=(string)$letras.(string)$numeroposicion;
                                   
                                     $sheet->setCellValue($celda2, $valor);  
                                   
                                }
                                else{
                                $fila = 0; 
                                  
                                 
                                    $sheet->setCellValue($celda, $valor); 
                                
                                } 

                                $archivo2 = $result[$colbol]["archivo"];
                                $variable2 = $result[$colbol]["nombrevariable"];
                                $varhoja = $result[$colbol]["hoja"];

                     }

                           
                      
                     }

                  
 

					 $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
					 $writer->save(Trim($_POST["ruta"])."/".$boleta.".xlsx");
            
 

                if($result){
                        $alerta=[
                            "status"=>"200",
                            "boleta"=>$boleta,
                            "tipo"=>"limpiar",
                            "titulo"=>"Generar Plantilla",
                            "texto"=>"Se genero el archivo correctamente",
                            "icono"=>"success"
                        ];	
                    }else{
                        $alerta=[
                            "status"=>"400",
                            "tipo"=>"simple",
                        "titulo"=>"Error",
                        "texto"=>"No se pudo generar el archivo.",
                        "icono"=>"error"
                        ];	
                    }
            }
            } catch (Exception  $e) {
                $alerta=[
                    "status"=>"400",
                    "tipo"=>"simple",
                "titulo"=>"Error",
                "texto"=>"Error consulte con el administrador",
                "icono"=>"error"
                ];	
        
                $log->guardarlog($e->getMessage()); 
            }
    
            echo json_encode($alerta); 
             
        }


          //Metodo POST para guardar los registros
          if(isset($_POST['generar'])=="generarplantilla")
          {
            try {
              $boleta=$_POST["cmb_boleta"];
              $formulario =$inscarga->obtenerdatos_formulario($_POST["cmb_formulario"]);

              
            if ( $boleta==0){
                $todaslasboletas =$inscarga->OBTENER_BOLETASFORMULARIO($formulario[0]["nombre"],$formulario[0]["anio"]);
            } else{
                $todaslasboletas[] = array(
                    'boleta'=>  $boleta,                    
                );
            }
    


              if (!is_dir( Trim($_POST["ruta"]))) {
                  $alerta=[
                      "tipo"=>"simple",
                      "titulo"=>"Error",
                      "texto"=>"Ruta indicada no existe, por favor validar.",
                      "icono"=>"error"
                  ];
              }else{ 
                   ini_set('max_execution_time', '0'); 
                   set_time_limit(0);
                   ini_set('memory_limit', '2048M'); 

                  $totalregistro =count($todaslasboletas);
                    for ($col = 0; $col <= $totalregistro-1; ++$col) {
                        $boleta=$todaslasboletas[$col]['boleta'];
                        $result =$inscarga->obtenervalorboleta_formulario($todaslasboletas[$col]['boleta'],$formulario[0]["nombre"],$formulario[0]["anio"]);
  
                        $inputFileType = 'Xlsx'; 
        
                        $rutaarchivo=$formulario[0]["ruta"]."/".$formulario[0]["nombre"];
        
                        //datos de la boleta para el formulario
        
        
                            //ejemplo para escribir en el excel
                            // $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                            // $reader->setLoadSheetsOnly(["Datos_Emp", "Datos","AnexosEmpresa"]);
                            // $spreadsheet = $reader->load($rutaarchivo);

                           
        
                            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($rutaarchivo);
                             
        
                             $archivo2 = "";
                             $variable2 = "";
                             $varhoja = "";
                             $fila = 0;
                             $totalfilas=count($result);
        
                             for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) {
                             $hoja=(string)$result[$colbol]['hoja'];
                             $celda=(string)$result[$colbol]['posicion'];
                             $valor =(string)$result[$colbol]['valor'];
                             $archivo =(string)$result[$colbol]['archivo'];
                             $variable =(string)$result[$colbol]['nombrevariable'];
                            
        
                             if ($celda <> "") {
                                 $numeroposicion = $inscarga->limpiarletras($celda);
                                 $letras = $inscarga->limpiarnumeros($celda);
                                 
                                 if ($hoja <> $varhoja){ 
                                    $sheet =$spreadsheet->getSheetByName($hoja);
                                 }
        
                                         
                                 if (($archivo == $archivo2) && ($variable == $variable2)) { 
                                            $fila = $fila + 1 ;
                                            $numeroposicion = $numeroposicion + $fila;
                                            $celda2=(string)$letras.(string)$numeroposicion;
                                           
                                             $sheet->setCellValue($celda2, $valor);  
                                           
                                        }
                                        else{
                                        $fila = 0; 
                                          
                                         
                                            $sheet->setCellValue($celda, $valor); 
                                        
                                        } 
        
                                        $archivo2 = $result[$colbol]["archivo"];
                                        $variable2 = $result[$colbol]["nombrevariable"];
                                        $varhoja = $result[$colbol]["hoja"];
        
                             }
        
                                   
                              
                             }

                              $nombreexcel=Trim($_POST["ruta"])."/".$todaslasboletas[$col]['boleta'].".xlsx";
                             if($formulario[0]["actividad"]=="Comercio"){
                                detallescomercio($spreadsheet,$boleta,$formulario);
                                
                             }

                             if($formulario[0]["actividad"]=="Industria"){
                                    if($formulario[0]["detalleact"]=="Industria manufacturera"){
                                        detallesIndustria($spreadsheet,$boleta,$formulario); 
                                        $datoempresa =$inscarga->obtenernombreempresa($boleta,$formulario[0]["nombre"]); 
                                        $nombreexcel=$datoempresa[0]["empresa"];
                                    }
                                
                                if($formulario[0]["detalleact"]=="Agropecuario"){
                                        detallesagropecuario($spreadsheet,$boleta,$formulario); 
                                    }
                             }

                              if($formulario[0]["actividad"]=="Servicios"){

                                    if($formulario[0]["detalleact"]=="salud"){
                                        detallesSalud($spreadsheet,$boleta,$formulario); 
                                    }

                                    if($formulario[0]["detalleact"]=="Educación"){
                                        detallesEducacion($spreadsheet,$boleta,$formulario); 
                                    }

                                    if($formulario[0]["detalleact"]=="Empresariales"){
                                        detallesEmpresarial($spreadsheet,$boleta,$formulario); 
                                    }
                                    
                                    if($formulario[0]["detalleact"]=="Restaurantes"){
                                        detallesRestaurantes($spreadsheet,$boleta,$formulario); 
                                    }

                                    if($formulario[0]["detalleact"]=="Hoteles"){
                                        detallesHoteles($spreadsheet,$boleta,$formulario); 
                                    }

                                    if($formulario[0]["detalleact"]=="Intermediación financiera"){
                                        detallesIF($spreadsheet,$boleta,$formulario); 
                                    }

                                    if($formulario[0]["detalleact"]=="Reparación de vehículos"){
                                        detallesvehiculo($spreadsheet,$boleta,$formulario); 
                                    }

                                    if($formulario[0]["detalleact"]=="Comuntarios"){
                                        detallescomunitario($spreadsheet,$boleta,$formulario); 
                                    }
                              }

                            

                           
        
                             $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                             $writer->save($nombreexcel);
                             $spreadsheet->disconnectWorksheets();
                             unset($spreadsheet);
                        
                    }
                  //ini_set('memory_limit', '1024'); 
                 
              
   
  
                  if($result){
                          $alerta=[
                              "status"=>"200",
                              "boleta"=>$boleta,
                              "tipo"=>"limpiar",
                              "titulo"=>"Generar Plantilla",
                              "texto"=>"Se genero el archivo correctamente para la boleta: ".$boleta ,
                              "icono"=>"success"
                          ];	
                      }else{
                          $alerta=[
                              "status"=>"400",
                              "tipo"=>"simple",
                          "titulo"=>"Error",
                          "texto"=>"No se pudo generar el archivo.",
                          "icono"=>"error"
                          ];	
                      }
              }
            } catch (Exception  $e) {
                $alerta=[
                    "status"=>"400",
                    "tipo"=>"simple",
                "titulo"=>"Error",
                "texto"=>"Error consulte con el administrador",
                "icono"=>"error"
                ];	
        
                $log->guardarlog($e->getMessage()); 
            }
      
              echo json_encode($alerta); 
               
          }


          function columnaALetra($columna) {
            $letra = '';
            while ($columna > 0) {
                $columna--;
                $letra = chr($columna % 26 + 65) . $letra;
                $columna = floor($columna / 26);
            }
            return $letra;
        }

       

        function reemplazarCaracteresEspeciales($texto) {
            // Mapeo de caracteres mal codificados a sus valores correctos
            $reemplazos = array(
                'Ã¡' => 'á', 'Ã¡' => 'á', 'Ã©' => 'é', 'Ã­' => 'í', 'Ã³' => 'ó', 'Ãº' => 'ú', 'Ã±' => 'ñ', 'Ã¿' => '¿', 'Ã‚' => 'Á','ÃA' => 'ÍA',
                'Ã‘' => 'Ñ', 'Ã‚' => 'Á', 'Ã¡' => 'á', 'Ã©' => 'é', 'Ã­' => 'í', 'Ã³' => 'ó', 'Ãº' => 'ú', 'â' => 'á', '´'=> '´'
            );
        
            // Realizamos el reemplazo
            $texto_corregido = strtr($texto, $reemplazos);
        
            return $texto_corregido;
        }

        function detallesagropecuario($spreadsheet,$boleta,$formulario){
            $inscarga = new generarController();
           //iniciamos con los detalles
             $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_AG_ANEXO2D.sav','OSP0001',$formulario[0]["nombre"]);
                                if ($resultposicion){
                            $hojagrupo = $resultposicion[0]["hoja"]; 
                            $posiciongrupo = $resultposicion[0]["posicion"];
                            $sheet =$spreadsheet->getSheetByName($hojagrupo);
                        
                            $fila =  $inscarga->limpiarletras($posiciongrupo);
                            $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_AG_ANEXO2D.sav',$formulario[0]["nombre"]);
                            $totalfilas=count($resultposicion);
                            $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                          
                                for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                    $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0003"]));                                        
                                    $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0004"]));



                                    $fila=$fila+1;

                                } 
                            }

                    $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_AG_ANEXO3D.sav','OGT0001',$formulario[0]["nombre"]);
                        if ($resultposicion){
                    $hojagrupo = $resultposicion[0]["hoja"]; 
                    $posiciongrupo = $resultposicion[0]["posicion"];
                    $sheet =$spreadsheet->getSheetByName($hojagrupo);
                
                    $fila =  $inscarga->limpiarletras($posiciongrupo);
                    $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_AG_ANEXO3D.sav',$formulario[0]["nombre"]);
                    $totalfilas=count($resultposicion);
                    $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                  
                        for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                            $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0003"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0004"]));



                            $fila=$fila+1;

                        } 
                    }
 

                          
        }

        function detallescomunitario($spreadsheet,$boleta,$formulario){
            $inscarga = new generarController();
           //iniciamos con los detalles
             $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_SC_Anexo2D.sav','OSP0001',$formulario[0]["nombre"]);
                                if ($resultposicion){
                            $hojagrupo = $resultposicion[0]["hoja"]; 
                            $posiciongrupo = $resultposicion[0]["posicion"];
                            $sheet =$spreadsheet->getSheetByName($hojagrupo);
                        
                            $fila =  $inscarga->limpiarletras($posiciongrupo);
                            $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_SC_Anexo2D.sav',$formulario[0]["nombre"]);
                            $totalfilas=count($resultposicion);
                            $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                          
                                for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                    $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0003"]));                                        
                                    $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0004"]));



                                    $fila=$fila+1;

                                } 
                            }

                    $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_SC_Anexo3D.sav','OGT0001',$formulario[0]["nombre"]);
                        if ($resultposicion){
                    $hojagrupo = $resultposicion[0]["hoja"]; 
                    $posiciongrupo = $resultposicion[0]["posicion"];
                    $sheet =$spreadsheet->getSheetByName($hojagrupo);
                
                    $fila =  $inscarga->limpiarletras($posiciongrupo);
                    $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_SC_Anexo3D.sav',$formulario[0]["nombre"]);
                    $totalfilas=count($resultposicion);
                    $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                  
                        for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                            $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0003"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0004"]));



                            $fila=$fila+1;

                        } 
                    }
 

                            $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('E_AIF_SC_Anexo2D.sav','OSP0001',$formulario[0]["nombre"]);
                            if ($resultposicion){
                        $hojagrupo = $resultposicion[0]["hoja"]; 
                        $posiciongrupo = $resultposicion[0]["posicion"];
                        $sheet =$spreadsheet->getSheetByName($hojagrupo);
                    
                        $fila =  $inscarga->limpiarletras($posiciongrupo);
                        $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'E_AIF_SC_Anexo2D.sav',$formulario[0]["nombre"]);
                        $totalfilas=count($resultposicion);
                        $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                      
                            for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0001"]));
                                $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0002"]));
                                $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0003"]));                                        
                                $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0004"]));



                                $fila=$fila+1;

                            } 
                        }
 

                    $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('E_AIF_SC_Anexo3D.sav','OGT0001',$formulario[0]["nombre"]);
                    if ($resultposicion){
                $hojagrupo = $resultposicion[0]["hoja"]; 
                $posiciongrupo = $resultposicion[0]["posicion"];
                $sheet =$spreadsheet->getSheetByName($hojagrupo);
            
                $fila =  $inscarga->limpiarletras($posiciongrupo);
                $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'E_AIF_SC_Anexo3D.sav',$formulario[0]["nombre"]);
                $totalfilas=count($resultposicion);
                $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
              
                    for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                        $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0003"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0004"]));



                        $fila=$fila+1;

                    } 
                }

                        $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_SC_Capitulo7.sav','MPU0001',$formulario[0]["nombre"]);
                        if ($resultposicion){
                    $hojagrupo = $resultposicion[0]["hoja"]; 
                    $posiciongrupo = $resultposicion[0]["posicion"];
                    $sheet =$spreadsheet->getSheetByName($hojagrupo);
                
                    $fila =  $inscarga->limpiarletras($posiciongrupo);
                    $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_SC_Capitulo7.sav',$formulario[0]["nombre"]);
                    $totalfilas=count($resultposicion);
                    $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                    
                        for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) {                                       
                            $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0001"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0003"]));                                                                                
                            $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0005"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0007"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+4).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0009"])); 
                            $sheet->setCellValue(columnaALetra($columnaNumero+5).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0025"]));   
                            $sheet->setCellValue(columnaALetra($columnaNumero+6).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0027"]));   
                            $sheet->setCellValue(columnaALetra($columnaNumero+7).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0039"]));  
                            $sheet->setCellValue(columnaALetra($columnaNumero+8).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0017"]));   
                            $sheet->setCellValue(columnaALetra($columnaNumero+9).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0029"]));  
                            $sheet->setCellValue(columnaALetra($columnaNumero+10).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0033"]));   
                            $sheet->setCellValue(columnaALetra($columnaNumero+11).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0019"])); 
                            $sheet->setCellValue(columnaALetra($columnaNumero+12).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0021"]));  
                            $sheet->setCellValue(columnaALetra($columnaNumero+16).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+17).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+18).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0003"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+19).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0004"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+20).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0005"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+21).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0006"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+22).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+23).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+24).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0003"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+25).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0004"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+26).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0005"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+27).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0006"]));    

                            $fila=$fila+1;

                        } 
                    }


                   $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_SC_Capitulo8.sav','MER0001',$formulario[0]["nombre"]);
                            if ($resultposicion){
                            $hojagrupo = $resultposicion[0]["hoja"]; 
                            $posiciongrupo = $resultposicion[0]["posicion"];
                            $sheet =$spreadsheet->getSheetByName($hojagrupo);
                        
                            $fila =  $inscarga->limpiarletras($posiciongrupo);
                            $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_SC_Capitulo8.sav',$formulario[0]["nombre"]);
                            $totalfilas=count($resultposicion);
                            $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                          
                                for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                    $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0004"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+4).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0005"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+5).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0006"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+6).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0016"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+7).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0017"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+8).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0021"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+9).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0010"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+10).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0011"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+11).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0018"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+12).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0013"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+13).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0014"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+14).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0015"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+22).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+23).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+24).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0003"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+25).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0004"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+26).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0005"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+27).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0006"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+28).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+29).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+30).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0003"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+31).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0004"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+32).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0005"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+33).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0006"]));
                                    
                                    
                                    
                                    $fila=$fila+1;

                                } 
                            }

 

 
        }

            function detallesvehiculo($spreadsheet,$boleta,$formulario){
            $inscarga = new generarController();
           //iniciamos con los detalles
             $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_VE_Anexo2D.sav','OSP0001',$formulario[0]["nombre"]);
                                if ($resultposicion){
                            $hojagrupo = $resultposicion[0]["hoja"]; 
                            $posiciongrupo = $resultposicion[0]["posicion"];
                            $sheet =$spreadsheet->getSheetByName($hojagrupo);
                        
                            $fila =  $inscarga->limpiarletras($posiciongrupo);
                            $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_VE_Anexo2D.sav',$formulario[0]["nombre"]);
                            $totalfilas=count($resultposicion);
                            $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                          
                                for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                    $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0003"]));                                        
                                    $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0004"]));



                                    $fila=$fila+1;

                                } 
                            }

                    $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_VE_Anexo3D.sav','OGT0001',$formulario[0]["nombre"]);
                        if ($resultposicion){
                    $hojagrupo = $resultposicion[0]["hoja"]; 
                    $posiciongrupo = $resultposicion[0]["posicion"];
                    $sheet =$spreadsheet->getSheetByName($hojagrupo);
                
                    $fila =  $inscarga->limpiarletras($posiciongrupo);
                    $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_VE_Anexo3D.sav',$formulario[0]["nombre"]);
                    $totalfilas=count($resultposicion);
                    $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                  
                        for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                            $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0003"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0004"]));



                            $fila=$fila+1;

                        } 
                    }
 

                            $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('E_AIF_VE_Anexo2D.sav','OSP0001',$formulario[0]["nombre"]);
                            if ($resultposicion){
                        $hojagrupo = $resultposicion[0]["hoja"]; 
                        $posiciongrupo = $resultposicion[0]["posicion"];
                        $sheet =$spreadsheet->getSheetByName($hojagrupo);
                    
                        $fila =  $inscarga->limpiarletras($posiciongrupo);
                        $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'E_AIF_VE_Anexo2D.sav',$formulario[0]["nombre"]);
                        $totalfilas=count($resultposicion);
                        $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                      
                            for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0001"]));
                                $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0002"]));
                                $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0003"]));                                        
                                $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0004"]));



                                $fila=$fila+1;

                            } 
                        }
 

                    $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('E_AIF_VE_Anexo3D.sav','OGT0001',$formulario[0]["nombre"]);
                    if ($resultposicion){
                $hojagrupo = $resultposicion[0]["hoja"]; 
                $posiciongrupo = $resultposicion[0]["posicion"];
                $sheet =$spreadsheet->getSheetByName($hojagrupo);
            
                $fila =  $inscarga->limpiarletras($posiciongrupo);
                $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'E_AIF_VE_Anexo3D.sav',$formulario[0]["nombre"]);
                $totalfilas=count($resultposicion);
                $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
              
                    for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                        $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0003"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0004"]));



                        $fila=$fila+1;

                    } 
                }

                        $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_VE_Capitulo7.sav','MPU0001',$formulario[0]["nombre"]);
                        if ($resultposicion){
                    $hojagrupo = $resultposicion[0]["hoja"]; 
                    $posiciongrupo = $resultposicion[0]["posicion"];
                    $sheet =$spreadsheet->getSheetByName($hojagrupo);
                
                    $fila =  $inscarga->limpiarletras($posiciongrupo);
                    $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_VE_Capitulo7.sav',$formulario[0]["nombre"]);
                    $totalfilas=count($resultposicion);
                    $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                    
                        for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) {                                       
                            $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0001"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0003"]));                                                                                
                            $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0005"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0007"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+4).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0009"])); 
                            $sheet->setCellValue(columnaALetra($columnaNumero+5).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0025"]));   
                            $sheet->setCellValue(columnaALetra($columnaNumero+6).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0027"]));   
                            $sheet->setCellValue(columnaALetra($columnaNumero+7).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0039"]));  
                            $sheet->setCellValue(columnaALetra($columnaNumero+8).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0017"]));   
                            $sheet->setCellValue(columnaALetra($columnaNumero+9).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0029"]));  
                            $sheet->setCellValue(columnaALetra($columnaNumero+10).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0033"]));   
                            $sheet->setCellValue(columnaALetra($columnaNumero+11).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0019"])); 
                            $sheet->setCellValue(columnaALetra($columnaNumero+12).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0021"]));  
                            $sheet->setCellValue(columnaALetra($columnaNumero+16).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+17).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+18).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0003"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+19).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0004"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+20).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0005"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+21).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0006"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+22).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+23).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+24).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0003"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+25).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0004"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+26).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0005"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+27).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0006"]));    

                            $fila=$fila+1;

                        } 
                    }


                   $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_VE_Capitulo8.sav','MER0001',$formulario[0]["nombre"]);
                            if ($resultposicion){
                            $hojagrupo = $resultposicion[0]["hoja"]; 
                            $posiciongrupo = $resultposicion[0]["posicion"];
                            $sheet =$spreadsheet->getSheetByName($hojagrupo);
                        
                            $fila =  $inscarga->limpiarletras($posiciongrupo);
                            $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_VE_Capitulo8.sav',$formulario[0]["nombre"]);
                            $totalfilas=count($resultposicion);
                            $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                          
                                for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                    $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0004"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+4).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0005"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+5).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0006"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+6).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0016"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+7).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0017"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+8).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0021"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+9).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0010"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+10).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0011"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+11).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0018"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+12).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0013"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+13).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0014"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+14).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0015"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+22).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+23).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+24).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0003"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+25).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0004"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+26).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0005"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+27).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0006"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+28).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+29).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+30).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0003"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+31).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0004"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+32).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0005"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+33).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0006"]));
                                    
                                    
                                    
                                    $fila=$fila+1;

                                } 
                            }

 

 
        }

        function detallesIF($spreadsheet,$boleta,$formulario){
            $inscarga = new generarController();
           //iniciamos con los detalles
             $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_IF_Anexo2D.sav','OSP0001',$formulario[0]["nombre"]);
                                if ($resultposicion){
                            $hojagrupo = $resultposicion[0]["hoja"]; 
                            $posiciongrupo = $resultposicion[0]["posicion"];
                            $sheet =$spreadsheet->getSheetByName($hojagrupo);
                        
                            $fila =  $inscarga->limpiarletras($posiciongrupo);
                            $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_IF_Anexo2D.sav',$formulario[0]["nombre"]);
                            $totalfilas=count($resultposicion);
                            $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                          
                                for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                    $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0003"]));                                        
                                    $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0004"]));



                                    $fila=$fila+1;

                                } 
                            }

                    $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_IF_Anexo3D.sav','OGT0001',$formulario[0]["nombre"]);
                        if ($resultposicion){
                    $hojagrupo = $resultposicion[0]["hoja"]; 
                    $posiciongrupo = $resultposicion[0]["posicion"];
                    $sheet =$spreadsheet->getSheetByName($hojagrupo);
                
                    $fila =  $inscarga->limpiarletras($posiciongrupo);
                    $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_IF_Anexo3D.sav',$formulario[0]["nombre"]);
                    $totalfilas=count($resultposicion);
                    $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                  
                        for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                            $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0003"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0004"]));



                            $fila=$fila+1;

                        } 
                    }
 

                            $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('E_AIF_IF_Anexo2D.sav','OSP0001',$formulario[0]["nombre"]);
                            if ($resultposicion){
                        $hojagrupo = $resultposicion[0]["hoja"]; 
                        $posiciongrupo = $resultposicion[0]["posicion"];
                        $sheet =$spreadsheet->getSheetByName($hojagrupo);
                    
                        $fila =  $inscarga->limpiarletras($posiciongrupo);
                        $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'E_AIF_IF_Anexo2D.sav',$formulario[0]["nombre"]);
                        $totalfilas=count($resultposicion);
                        $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                      
                            for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0001"]));
                                $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0002"]));
                                $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0003"]));                                        
                                $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0004"]));



                                $fila=$fila+1;

                            } 
                        }
 

                    $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('E_AIF_IF_Anexo3D.sav','OGT0001',$formulario[0]["nombre"]);
                    if ($resultposicion){
                $hojagrupo = $resultposicion[0]["hoja"]; 
                $posiciongrupo = $resultposicion[0]["posicion"];
                $sheet =$spreadsheet->getSheetByName($hojagrupo);
            
                $fila =  $inscarga->limpiarletras($posiciongrupo);
                $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'E_AIF_IF_Anexo3D.sav',$formulario[0]["nombre"]);
                $totalfilas=count($resultposicion);
                $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
              
                    for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                        $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0003"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0004"]));



                        $fila=$fila+1;

                    } 
                }

                        $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_IF_Capitulo7.sav','MPU0001',$formulario[0]["nombre"]);
                        if ($resultposicion){
                    $hojagrupo = $resultposicion[0]["hoja"]; 
                    $posiciongrupo = $resultposicion[0]["posicion"];
                    $sheet =$spreadsheet->getSheetByName($hojagrupo);
                
                    $fila =  $inscarga->limpiarletras($posiciongrupo);
                    $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_IF_Capitulo7.sav',$formulario[0]["nombre"]);
                    $totalfilas=count($resultposicion);
                    $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                    
                        for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) {                                       
                            $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0001"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0003"]));                                                                                
                            $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0005"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0007"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+4).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0009"])); 
                            $sheet->setCellValue(columnaALetra($columnaNumero+5).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0025"]));   
                            $sheet->setCellValue(columnaALetra($columnaNumero+6).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0027"]));   
                            $sheet->setCellValue(columnaALetra($columnaNumero+7).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0039"]));  
                            $sheet->setCellValue(columnaALetra($columnaNumero+8).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0017"]));   
                            $sheet->setCellValue(columnaALetra($columnaNumero+9).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0029"]));  
                            $sheet->setCellValue(columnaALetra($columnaNumero+10).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0033"]));   
                            $sheet->setCellValue(columnaALetra($columnaNumero+11).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0019"])); 
                            $sheet->setCellValue(columnaALetra($columnaNumero+12).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0021"]));  
                            $sheet->setCellValue(columnaALetra($columnaNumero+16).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+17).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+18).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0003"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+19).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0004"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+20).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0005"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+21).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0006"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+22).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+23).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+24).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0003"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+25).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0004"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+26).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0005"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+27).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0006"]));    

                            $fila=$fila+1;

                        } 
                    }


                   $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_IF_Capitulo8.sav','MER0001',$formulario[0]["nombre"]);
                            if ($resultposicion){
                            $hojagrupo = $resultposicion[0]["hoja"]; 
                            $posiciongrupo = $resultposicion[0]["posicion"];
                            $sheet =$spreadsheet->getSheetByName($hojagrupo);
                        
                            $fila =  $inscarga->limpiarletras($posiciongrupo);
                            $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_IF_Capitulo8.sav',$formulario[0]["nombre"]);
                            $totalfilas=count($resultposicion);
                            $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                          
                                for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                    $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0004"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+4).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0005"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+5).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0006"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+6).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0016"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+7).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0017"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+8).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0021"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+9).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0010"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+10).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0011"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+11).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0018"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+12).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0013"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+13).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0014"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+14).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0015"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+22).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+23).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+24).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0003"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+25).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0004"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+26).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0005"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+27).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0006"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+28).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+29).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+30).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0003"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+31).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0004"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+32).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0005"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+33).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0006"]));
                                    
                                    
                                    
                                    $fila=$fila+1;

                                } 
                            }

 

 
        }

        function detallesHoteles($spreadsheet,$boleta,$formulario){
            $inscarga = new generarController();
           //iniciamos con los detalles
             $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_H_Anexo2D.sav','OSP0001',$formulario[0]["nombre"]);
                                if ($resultposicion){
                            $hojagrupo = $resultposicion[0]["hoja"]; 
                            $posiciongrupo = $resultposicion[0]["posicion"];
                            $sheet =$spreadsheet->getSheetByName($hojagrupo);
                        
                            $fila =  $inscarga->limpiarletras($posiciongrupo);
                            $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_H_Anexo2D.sav',$formulario[0]["nombre"]);
                            $totalfilas=count($resultposicion);
                            $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                          
                                for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                    $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0003"]));                                        
                                    $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0004"]));



                                    $fila=$fila+1;

                                } 
                            }

                    $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_H_Anexo3D.sav','OGT0001',$formulario[0]["nombre"]);
                        if ($resultposicion){
                    $hojagrupo = $resultposicion[0]["hoja"]; 
                    $posiciongrupo = $resultposicion[0]["posicion"];
                    $sheet =$spreadsheet->getSheetByName($hojagrupo);
                
                    $fila =  $inscarga->limpiarletras($posiciongrupo);
                    $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_H_Anexo3D.sav',$formulario[0]["nombre"]);
                    $totalfilas=count($resultposicion);
                    $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                  
                        for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                            $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0003"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0004"]));



                            $fila=$fila+1;

                        } 
                    }
 

                            $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('E_AIF_H_Anexo2D.sav','OSP0001',$formulario[0]["nombre"]);
                            if ($resultposicion){
                        $hojagrupo = $resultposicion[0]["hoja"]; 
                        $posiciongrupo = $resultposicion[0]["posicion"];
                        $sheet =$spreadsheet->getSheetByName($hojagrupo);
                    
                        $fila =  $inscarga->limpiarletras($posiciongrupo);
                        $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'E_AIF_H_Anexo2D.sav',$formulario[0]["nombre"]);
                        $totalfilas=count($resultposicion);
                        $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                      
                            for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0001"]));
                                $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0002"]));
                                $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0003"]));                                        
                                $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0004"]));



                                $fila=$fila+1;

                            } 
                        }
 

                    $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('E_AIF_H_Anexo3D.sav','OGT0001',$formulario[0]["nombre"]);
                    if ($resultposicion){
                $hojagrupo = $resultposicion[0]["hoja"]; 
                $posiciongrupo = $resultposicion[0]["posicion"];
                $sheet =$spreadsheet->getSheetByName($hojagrupo);
            
                $fila =  $inscarga->limpiarletras($posiciongrupo);
                $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'E_AIF_H_Anexo3D.sav',$formulario[0]["nombre"]);
                $totalfilas=count($resultposicion);
                $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
              
                    for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                        $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0003"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0004"]));



                        $fila=$fila+1;

                    } 
                }

                        $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_H_Capitulo7.sav','MPU0001',$formulario[0]["nombre"]);
                        if ($resultposicion){
                    $hojagrupo = $resultposicion[0]["hoja"]; 
                    $posiciongrupo = $resultposicion[0]["posicion"];
                    $sheet =$spreadsheet->getSheetByName($hojagrupo);
                
                    $fila =  $inscarga->limpiarletras($posiciongrupo);
                    $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_H_Capitulo7.sav',$formulario[0]["nombre"]);
                    $totalfilas=count($resultposicion);
                    $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                    
                        for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) {                                       
                            $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0001"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0003"]));                                                                                
                            $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0005"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0007"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+4).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0009"])); 
                            $sheet->setCellValue(columnaALetra($columnaNumero+5).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0025"]));   
                            $sheet->setCellValue(columnaALetra($columnaNumero+6).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0027"]));   
                            $sheet->setCellValue(columnaALetra($columnaNumero+7).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0039"]));  
                            $sheet->setCellValue(columnaALetra($columnaNumero+8).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0017"]));   
                            $sheet->setCellValue(columnaALetra($columnaNumero+9).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0029"]));  
                            $sheet->setCellValue(columnaALetra($columnaNumero+10).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0033"]));   
                            $sheet->setCellValue(columnaALetra($columnaNumero+11).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0019"])); 
                            $sheet->setCellValue(columnaALetra($columnaNumero+12).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0021"]));  
                            $sheet->setCellValue(columnaALetra($columnaNumero+16).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+17).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+18).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0003"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+19).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0004"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+20).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0005"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+21).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0006"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+22).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+23).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+24).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0003"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+25).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0004"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+26).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0005"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+27).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0006"]));    

                            $fila=$fila+1;

                        } 
                    }


                   $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_H_Capitulo8.sav','MER0001',$formulario[0]["nombre"]);
                            if ($resultposicion){
                            $hojagrupo = $resultposicion[0]["hoja"]; 
                            $posiciongrupo = $resultposicion[0]["posicion"];
                            $sheet =$spreadsheet->getSheetByName($hojagrupo);
                        
                            $fila =  $inscarga->limpiarletras($posiciongrupo);
                            $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_H_Capitulo8.sav',$formulario[0]["nombre"]);
                            $totalfilas=count($resultposicion);
                            $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                          
                                for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                    $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0004"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+4).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0005"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+5).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0006"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+6).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0016"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+7).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0017"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+8).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0021"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+9).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0010"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+10).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0011"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+11).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0018"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+12).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0013"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+13).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0014"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+14).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0015"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+22).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+23).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+24).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0003"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+25).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0004"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+26).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0005"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+27).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0006"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+28).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+29).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+30).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0003"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+31).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0004"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+32).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0005"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+33).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0006"]));
                                    
                                    
                                    
                                    $fila=$fila+1;

                                } 
                            }

 

 
        }

        function detallesRestaurantes($spreadsheet,$boleta,$formulario){
            $inscarga = new generarController();
           //iniciamos con los detalles
             $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_R_Anexo2D.sav','OSP0001',$formulario[0]["nombre"]);
                                if ($resultposicion){
                            $hojagrupo = $resultposicion[0]["hoja"]; 
                            $posiciongrupo = $resultposicion[0]["posicion"];
                            $sheet =$spreadsheet->getSheetByName($hojagrupo);
                        
                            $fila =  $inscarga->limpiarletras($posiciongrupo);
                            $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_R_Anexo2D.sav',$formulario[0]["nombre"]);
                            $totalfilas=count($resultposicion);
                            $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                          
                                for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                    $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0003"]));                                        
                                    $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0004"]));



                                    $fila=$fila+1;

                                } 
                            }

                    $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_R_Anexo3D.sav','OGT0001',$formulario[0]["nombre"]);
                        if ($resultposicion){
                    $hojagrupo = $resultposicion[0]["hoja"]; 
                    $posiciongrupo = $resultposicion[0]["posicion"];
                    $sheet =$spreadsheet->getSheetByName($hojagrupo);
                
                    $fila =  $inscarga->limpiarletras($posiciongrupo);
                    $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_R_Anexo3D.sav',$formulario[0]["nombre"]);
                    $totalfilas=count($resultposicion);
                    $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                  
                        for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                            $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0003"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0004"]));



                            $fila=$fila+1;

                        } 
                    }

               

                              

                            $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('E_AIF_R_Anexo2D.sav','OSP0001',$formulario[0]["nombre"]);
                            if ($resultposicion){
                        $hojagrupo = $resultposicion[0]["hoja"]; 
                        $posiciongrupo = $resultposicion[0]["posicion"];
                        $sheet =$spreadsheet->getSheetByName($hojagrupo);
                    
                        $fila =  $inscarga->limpiarletras($posiciongrupo);
                        $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'E_AIF_R_Anexo2D.sav',$formulario[0]["nombre"]);
                        $totalfilas=count($resultposicion);
                        $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                      
                            for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0001"]));
                                $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0002"]));
                                $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0003"]));                                        
                                $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0004"]));



                                $fila=$fila+1;

                            } 
                        }




                    $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('E_AIF_R_Anexo3D.sav','OGT0001',$formulario[0]["nombre"]);
                    if ($resultposicion){
                $hojagrupo = $resultposicion[0]["hoja"]; 
                $posiciongrupo = $resultposicion[0]["posicion"];
                $sheet =$spreadsheet->getSheetByName($hojagrupo);
            
                $fila =  $inscarga->limpiarletras($posiciongrupo);
                $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'E_AIF_R_Anexo3D.sav',$formulario[0]["nombre"]);
                $totalfilas=count($resultposicion);
                $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
              
                    for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                        $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0003"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0004"]));



                        $fila=$fila+1;

                    } 
                }

                        $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_R_Capitulo7.sav','MPU0001',$formulario[0]["nombre"]);
                        if ($resultposicion){
                    $hojagrupo = $resultposicion[0]["hoja"]; 
                    $posiciongrupo = $resultposicion[0]["posicion"];
                    $sheet =$spreadsheet->getSheetByName($hojagrupo);
                
                    $fila =  $inscarga->limpiarletras($posiciongrupo);
                    $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_R_Capitulo7.sav',$formulario[0]["nombre"]);
                    $totalfilas=count($resultposicion);
                    $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                    
                        for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) {                                       
                            $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0001"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0003"]));                                                                                
                            $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0005"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0007"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+4).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0009"])); 
                            $sheet->setCellValue(columnaALetra($columnaNumero+5).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0025"]));   
                            $sheet->setCellValue(columnaALetra($columnaNumero+6).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0027"]));   
                            $sheet->setCellValue(columnaALetra($columnaNumero+7).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0039"]));  
                            $sheet->setCellValue(columnaALetra($columnaNumero+8).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0017"]));   
                            $sheet->setCellValue(columnaALetra($columnaNumero+9).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0029"]));  
                            $sheet->setCellValue(columnaALetra($columnaNumero+10).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0033"]));   
                            $sheet->setCellValue(columnaALetra($columnaNumero+11).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0019"])); 
                            $sheet->setCellValue(columnaALetra($columnaNumero+12).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0021"]));  
                            $sheet->setCellValue(columnaALetra($columnaNumero+16).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+17).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+18).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0003"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+19).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0004"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+20).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0005"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+21).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0006"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+22).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+23).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+24).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0003"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+25).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0004"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+26).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0005"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+27).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0006"]));    

                            $fila=$fila+1;

                        } 
                    }


                   $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_R_Capitulo8.sav','MER0001',$formulario[0]["nombre"]);
                            if ($resultposicion){
                            $hojagrupo = $resultposicion[0]["hoja"]; 
                            $posiciongrupo = $resultposicion[0]["posicion"];
                            $sheet =$spreadsheet->getSheetByName($hojagrupo);
                        
                            $fila =  $inscarga->limpiarletras($posiciongrupo);
                            $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_R_Capitulo8.sav',$formulario[0]["nombre"]);
                            $totalfilas=count($resultposicion);
                            $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                          
                                for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                    $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0004"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+4).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0005"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+5).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0006"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+6).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0016"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+7).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0017"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+8).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0021"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+9).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0010"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+10).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0011"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+11).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0018"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+12).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0013"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+13).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0014"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+14).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0015"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+22).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+23).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+24).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0003"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+25).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0004"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+26).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0005"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+27).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0006"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+28).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+29).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+30).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0003"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+31).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0004"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+32).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0005"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+33).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0006"]));
                                    
                                    
                                    
                                    $fila=$fila+1;

                                } 
                            }

 

 
        }

        function detallesEmpresarial($spreadsheet,$boleta,$formulario){
            $inscarga = new generarController();
           //iniciamos con los detalles
             $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_SE_Anexo2D.sav','OSP0001',$formulario[0]["nombre"]);
                                if ($resultposicion){
                            $hojagrupo = $resultposicion[0]["hoja"]; 
                            $posiciongrupo = $resultposicion[0]["posicion"];
                            $sheet =$spreadsheet->getSheetByName($hojagrupo);
                        
                            $fila =  $inscarga->limpiarletras($posiciongrupo);
                            $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_SE_Anexo2D.sav',$formulario[0]["nombre"]);
                            $totalfilas=count($resultposicion);
                            $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                          
                                for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                    $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0003"]));                                        
                                    $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0004"]));



                                    $fila=$fila+1;

                                } 
                            }

                    $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_SE_Anexo3D.sav','OGT0001',$formulario[0]["nombre"]);
                        if ($resultposicion){
                    $hojagrupo = $resultposicion[0]["hoja"]; 
                    $posiciongrupo = $resultposicion[0]["posicion"];
                    $sheet =$spreadsheet->getSheetByName($hojagrupo);
                
                    $fila =  $inscarga->limpiarletras($posiciongrupo);
                    $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_SE_Anexo3D.sav',$formulario[0]["nombre"]);
                    $totalfilas=count($resultposicion);
                    $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                  
                        for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                            $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0003"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0004"]));



                            $fila=$fila+1;

                        } 
                    }

               

                              

                            $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('E_AIF_SE_Anexo2D.sav','OSP0001',$formulario[0]["nombre"]);
                            if ($resultposicion){
                        $hojagrupo = $resultposicion[0]["hoja"]; 
                        $posiciongrupo = $resultposicion[0]["posicion"];
                        $sheet =$spreadsheet->getSheetByName($hojagrupo);
                    
                        $fila =  $inscarga->limpiarletras($posiciongrupo);
                        $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'E_AIF_SE_Anexo2D.sav',$formulario[0]["nombre"]);
                        $totalfilas=count($resultposicion);
                        $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                      
                            for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0001"]));
                                $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0002"]));
                                $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0003"]));                                        
                                $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0004"]));



                                $fila=$fila+1;

                            } 
                        }




                    $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('E_AIF_SE_Anexo3D.sav','OGT0001',$formulario[0]["nombre"]);
                    if ($resultposicion){
                $hojagrupo = $resultposicion[0]["hoja"]; 
                $posiciongrupo = $resultposicion[0]["posicion"];
                $sheet =$spreadsheet->getSheetByName($hojagrupo);
            
                $fila =  $inscarga->limpiarletras($posiciongrupo);
                $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'E_AIF_SE_Anexo3D.sav',$formulario[0]["nombre"]);
                $totalfilas=count($resultposicion);
                $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
              
                    for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                        $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0003"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0004"]));



                        $fila=$fila+1;

                    } 
                }

                        $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_SE_Capitulo7.sav','MPU0001',$formulario[0]["nombre"]);
                        if ($resultposicion){
                    $hojagrupo = $resultposicion[0]["hoja"]; 
                    $posiciongrupo = $resultposicion[0]["posicion"];
                    $sheet =$spreadsheet->getSheetByName($hojagrupo);
                
                    $fila =  $inscarga->limpiarletras($posiciongrupo);
                    $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_SE_Capitulo7.sav',$formulario[0]["nombre"]);
                    $totalfilas=count($resultposicion);
                    $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                    
                        for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) {                                       
                            $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0001"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0003"]));                                                                                
                            $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0005"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0007"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+4).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0009"])); 
                            $sheet->setCellValue(columnaALetra($columnaNumero+5).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0025"]));   
                            $sheet->setCellValue(columnaALetra($columnaNumero+6).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0027"]));   
                            $sheet->setCellValue(columnaALetra($columnaNumero+7).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0039"]));  
                            $sheet->setCellValue(columnaALetra($columnaNumero+8).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0017"]));   
                            $sheet->setCellValue(columnaALetra($columnaNumero+9).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0029"]));  
                            $sheet->setCellValue(columnaALetra($columnaNumero+10).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0033"]));   
                            $sheet->setCellValue(columnaALetra($columnaNumero+11).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0019"])); 
                            $sheet->setCellValue(columnaALetra($columnaNumero+12).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0021"]));  
                            $sheet->setCellValue(columnaALetra($columnaNumero+16).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+17).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+18).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0003"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+19).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0004"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+20).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0005"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+21).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0006"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+22).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+23).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+24).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0003"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+25).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0004"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+26).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0005"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+27).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0006"]));    

                            $fila=$fila+1;

                        } 
                    }


                   $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_SE_Capitulo8.sav','MER0001',$formulario[0]["nombre"]);
                            if ($resultposicion){
                            $hojagrupo = $resultposicion[0]["hoja"]; 
                            $posiciongrupo = $resultposicion[0]["posicion"];
                            $sheet =$spreadsheet->getSheetByName($hojagrupo);
                        
                            $fila =  $inscarga->limpiarletras($posiciongrupo);
                            $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_SE_Capitulo8.sav',$formulario[0]["nombre"]);
                            $totalfilas=count($resultposicion);
                            $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                          
                                for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                    $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0004"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+4).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0005"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+5).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0006"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+6).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0016"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+7).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0017"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+8).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0021"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+9).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0010"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+10).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0011"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+11).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0018"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+12).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0013"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+13).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0014"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+14).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0015"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+22).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+23).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+24).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0003"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+25).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0004"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+26).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0005"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+27).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0006"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+28).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+29).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+30).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0003"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+31).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0004"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+32).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0005"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+33).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0006"]));
                                    
                                    
                                    
                                    $fila=$fila+1;

                                } 
                            }

 

 
        }

        function detallesEducacion($spreadsheet,$boleta,$formulario){
            $inscarga = new generarController();
           //iniciamos con los detalles
             $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_EN_Anexo2_D.sav','OSP0001',$formulario[0]["nombre"]);
                                if ($resultposicion){
                            $hojagrupo = $resultposicion[0]["hoja"]; 
                            $posiciongrupo = $resultposicion[0]["posicion"];
                            $sheet =$spreadsheet->getSheetByName($hojagrupo);
                        
                            $fila =  $inscarga->limpiarletras($posiciongrupo);
                            $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_EN_Anexo2_D.sav',$formulario[0]["nombre"]);
                            $totalfilas=count($resultposicion);
                            $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                          
                                for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                    $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0003"]));                                        
                                    $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0004"]));



                                    $fila=$fila+1;

                                } 
                            }

                    $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_EN_Anexo3_D.sav','OGT0001',$formulario[0]["nombre"]);
                        if ($resultposicion){
                    $hojagrupo = $resultposicion[0]["hoja"]; 
                    $posiciongrupo = $resultposicion[0]["posicion"];
                    $sheet =$spreadsheet->getSheetByName($hojagrupo);
                
                    $fila =  $inscarga->limpiarletras($posiciongrupo);
                    $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_EN_Anexo3_D.sav',$formulario[0]["nombre"]);
                    $totalfilas=count($resultposicion);
                    $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                  
                        for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                            $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0003"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0004"]));



                            $fila=$fila+1;

                        } 
                    }

               

                              

                            $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('E_AIF_EN_Anexo2_D.sav','OSP0001',$formulario[0]["nombre"]);
                            if ($resultposicion){
                        $hojagrupo = $resultposicion[0]["hoja"]; 
                        $posiciongrupo = $resultposicion[0]["posicion"];
                        $sheet =$spreadsheet->getSheetByName($hojagrupo);
                    
                        $fila =  $inscarga->limpiarletras($posiciongrupo);
                        $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'E_AIF_EN_Anexo2_D.sav',$formulario[0]["nombre"]);
                        $totalfilas=count($resultposicion);
                        $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                      
                            for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0001"]));
                                $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0002"]));
                                $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0003"]));                                        
                                $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0004"]));



                                $fila=$fila+1;

                            } 
                        }




                    $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('E_AIF_EN_Anexo3_D.sav','OGT0001',$formulario[0]["nombre"]);
                    if ($resultposicion){
                $hojagrupo = $resultposicion[0]["hoja"]; 
                $posiciongrupo = $resultposicion[0]["posicion"];
                $sheet =$spreadsheet->getSheetByName($hojagrupo);
            
                $fila =  $inscarga->limpiarletras($posiciongrupo);
                $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'E_AIF_EN_Anexo3_D.sav',$formulario[0]["nombre"]);
                $totalfilas=count($resultposicion);
                $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
              
                    for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                        $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0003"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0004"]));



                        $fila=$fila+1;

                    } 
                }

                        $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_EN_Capitulo7.sav','MPU0001',$formulario[0]["nombre"]);
                        if ($resultposicion){
                    $hojagrupo = $resultposicion[0]["hoja"]; 
                    $posiciongrupo = $resultposicion[0]["posicion"];
                    $sheet =$spreadsheet->getSheetByName($hojagrupo);
                
                    $fila =  $inscarga->limpiarletras($posiciongrupo);
                    $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_EN_Capitulo7.sav',$formulario[0]["nombre"]);
                    $totalfilas=count($resultposicion);
                    $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                    
                        for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) {                                       
                            $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0001"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0003"]));                                                                                
                            $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0005"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0007"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+4).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0008"])); 
                            $sheet->setCellValue(columnaALetra($columnaNumero+5).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0025"]));   
                            $sheet->setCellValue(columnaALetra($columnaNumero+6).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0027"]));   
                            $sheet->setCellValue(columnaALetra($columnaNumero+7).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0039"]));  
                            $sheet->setCellValue(columnaALetra($columnaNumero+8).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0017"]));   
                            $sheet->setCellValue(columnaALetra($columnaNumero+9).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0029"]));  
                            $sheet->setCellValue(columnaALetra($columnaNumero+10).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0033"]));   
                            $sheet->setCellValue(columnaALetra($columnaNumero+11).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0019"])); 
                            $sheet->setCellValue(columnaALetra($columnaNumero+12).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0021"]));  
                            $sheet->setCellValue(columnaALetra($columnaNumero+16).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+17).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+18).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0003"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+19).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0004"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+20).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0005"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+21).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvit0006"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+22).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+23).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+24).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0003"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+25).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0004"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+26).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0005"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+27).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0006"]));    

                            $fila=$fila+1;

                        } 
                    }


                   $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_EN_Capitulo8.sav','MER0001',$formulario[0]["nombre"]);
                            if ($resultposicion){
                            $hojagrupo = $resultposicion[0]["hoja"]; 
                            $posiciongrupo = $resultposicion[0]["posicion"];
                            $sheet =$spreadsheet->getSheetByName($hojagrupo);
                        
                            $fila =  $inscarga->limpiarletras($posiciongrupo);
                            $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_EN_Capitulo8.sav',$formulario[0]["nombre"]);
                            $totalfilas=count($resultposicion);
                            $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                          
                                for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                    $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0004"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+4).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0005"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+5).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0006"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+6).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0016"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+7).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0017"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+8).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0021"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+9).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0010"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+10).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0011"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+11).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0018"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+12).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0013"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+13).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0014"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+14).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0015"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+22).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+23).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+24).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0003"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+25).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0004"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+26).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0005"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+27).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0006"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+28).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+29).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+30).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0003"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+31).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0004"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+32).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0005"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+33).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0006"]));
                                    
                                    
                                    
                                    $fila=$fila+1;

                                } 
                            }

 

 
        }


        function detallesSalud($spreadsheet,$boleta,$formulario){
            $inscarga = new generarController();
           //iniciamos con los detalles
             $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_SA_Anexo2_D.sav','OSP0001',$formulario[0]["nombre"]);
                                if ($resultposicion){
                            $hojagrupo = $resultposicion[0]["hoja"]; 
                            $posiciongrupo = $resultposicion[0]["posicion"];
                            $sheet =$spreadsheet->getSheetByName($hojagrupo);
                        
                            $fila =  $inscarga->limpiarletras($posiciongrupo);
                            $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_SA_Anexo2_D.sav',$formulario[0]["nombre"]);
                            $totalfilas=count($resultposicion);
                            $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                          
                                for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                    $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0003"]));                                        
                                    $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0004"]));



                                    $fila=$fila+1;

                                } 
                            }

                    $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_SA_Anexo3_D.sav','OGT0001',$formulario[0]["nombre"]);
                        if ($resultposicion){
                    $hojagrupo = $resultposicion[0]["hoja"]; 
                    $posiciongrupo = $resultposicion[0]["posicion"];
                    $sheet =$spreadsheet->getSheetByName($hojagrupo);
                
                    $fila =  $inscarga->limpiarletras($posiciongrupo);
                    $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_SA_Anexo3_D.sav',$formulario[0]["nombre"]);
                    $totalfilas=count($resultposicion);
                    $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                  
                        for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                            $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0003"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0004"]));



                            $fila=$fila+1;

                        } 
                    }

                                   $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_SA_Capitulo7.sav','MPU0001',$formulario[0]["nombre"]);
                                    if ($resultposicion){
                                $hojagrupo = $resultposicion[0]["hoja"]; 
                                $posiciongrupo = $resultposicion[0]["posicion"];
                                $sheet =$spreadsheet->getSheetByName($hojagrupo);
                            
                                $fila =  $inscarga->limpiarletras($posiciongrupo);
                                $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_SA_Capitulo7.sav',$formulario[0]["nombre"]);
                                $totalfilas=count($resultposicion);
                                $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                              
                                    for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) {                                       
                                        $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0001"]));                                        
                                        $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0003"]));                                                                                
                                        $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0005"]));                                        
                                        $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0007"]));                                        
                                        $sheet->setCellValue(columnaALetra($columnaNumero+4).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0009"])); 
                                        $sheet->setCellValue(columnaALetra($columnaNumero+5).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0025"]));   
                                        $sheet->setCellValue(columnaALetra($columnaNumero+6).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0027"]));   
                                        $sheet->setCellValue(columnaALetra($columnaNumero+7).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0039"]));  
                                        $sheet->setCellValue(columnaALetra($columnaNumero+8).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0017"]));   
                                        $sheet->setCellValue(columnaALetra($columnaNumero+9).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0029"]));  
                                        $sheet->setCellValue(columnaALetra($columnaNumero+10).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0033"]));   
                                        $sheet->setCellValue(columnaALetra($columnaNumero+11).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0019"])); 
                                        $sheet->setCellValue(columnaALetra($columnaNumero+12).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0021"]));      
    
                                        $fila=$fila+1;
    
                                    } 
                                }


                              

                            $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('E_AIF_SA_Anexo2_D.sav','OSP0001',$formulario[0]["nombre"]);
                            if ($resultposicion){
                        $hojagrupo = $resultposicion[0]["hoja"]; 
                        $posiciongrupo = $resultposicion[0]["posicion"];
                        $sheet =$spreadsheet->getSheetByName($hojagrupo);
                    
                        $fila =  $inscarga->limpiarletras($posiciongrupo);
                        $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'E_AIF_SA_Anexo2_D.sav',$formulario[0]["nombre"]);
                        $totalfilas=count($resultposicion);
                        $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                      
                            for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0001"]));
                                $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0002"]));
                                $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0003"]));                                        
                                $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0004"]));



                                $fila=$fila+1;

                            } 
                        }




                    $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('E_AIF_SA_Anexo3_D.sav','OGT0001',$formulario[0]["nombre"]);
                    if ($resultposicion){
                $hojagrupo = $resultposicion[0]["hoja"]; 
                $posiciongrupo = $resultposicion[0]["posicion"];
                $sheet =$spreadsheet->getSheetByName($hojagrupo);
            
                $fila =  $inscarga->limpiarletras($posiciongrupo);
                $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'E_AIF_SA_Anexo3_D.sav',$formulario[0]["nombre"]);
                $totalfilas=count($resultposicion);
                $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
              
                    for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                        $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0003"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0004"]));



                        $fila=$fila+1;

                    } 
                }

                   $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_SA_Capitulo8.sav','MER0001',$formulario[0]["nombre"]);
                            if ($resultposicion){
                            $hojagrupo = $resultposicion[0]["hoja"]; 
                            $posiciongrupo = $resultposicion[0]["posicion"];
                            $sheet =$spreadsheet->getSheetByName($hojagrupo);
                        
                            $fila =  $inscarga->limpiarletras($posiciongrupo);
                            $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_SA_Capitulo8.sav',$formulario[0]["nombre"]);
                            $totalfilas=count($resultposicion);
                            $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                          
                                for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                    $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0004"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+4).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0005"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+5).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0006"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+6).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0016"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+7).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0017"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+8).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0021"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+9).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0010"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+10).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0011"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+11).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0018"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+12).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0013"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+13).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0014"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+14).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0015"]));
                                    
                                    
                                    $fila=$fila+1;

                                } 
                            }

 

 
        }

        function detallesIndustria($spreadsheet,$boleta,$formulario){
            $inscarga = new generarController();
           //iniciamos con los detalles
             $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_IND_Anexo2_D.sav','OSP0001',$formulario[0]["nombre"]);
                                if ($resultposicion){
                            $hojagrupo = $resultposicion[0]["hoja"]; 
                            $posiciongrupo = $resultposicion[0]["posicion"];
                            $sheet =$spreadsheet->getSheetByName($hojagrupo);
                        
                            $fila =  $inscarga->limpiarletras($posiciongrupo);
                            $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_IND_Anexo2_D.sav',$formulario[0]["nombre"]);
                            $totalfilas=count($resultposicion);
                            $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                          
                                for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                    $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0003"]));                                        
                                    $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0004"]));



                                    $fila=$fila+1;

                                } 
                            }

                    $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_IND_Anexo3_D.sav','OGT0001',$formulario[0]["nombre"]);
                        if ($resultposicion){
                    $hojagrupo = $resultposicion[0]["hoja"]; 
                    $posiciongrupo = $resultposicion[0]["posicion"];
                    $sheet =$spreadsheet->getSheetByName($hojagrupo);
                
                    $fila =  $inscarga->limpiarletras($posiciongrupo);
                    $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_IND_Anexo3_D.sav',$formulario[0]["nombre"]);
                    $totalfilas=count($resultposicion);
                    $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                  
                        for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                            $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0003"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0004"]));



                            $fila=$fila+1;

                        } 
                    }

                      $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_IND_Capitulo6.sav','PRO0001',$formulario[0]["nombre"]);
                                    if ($resultposicion){
                                $hojagrupo = $resultposicion[0]["hoja"]; 
                                $posiciongrupo = $resultposicion[0]["posicion"];
                                $sheet =$spreadsheet->getSheetByName($hojagrupo);
                            
                                $fila =  $inscarga->limpiarletras($posiciongrupo);
                                $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_IND_Capitulo6.sav',$formulario[0]["nombre"]);
                                $totalfilas=count($resultposicion);
                                $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                              
                                    for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                        $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["pro0001"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["pro0002"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["pro0003"]));                                        
                                        $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["pro0004"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+4).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["pro0005"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+5).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["pro0006"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+6).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["pro0007"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+7).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["pro0008"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+8).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["precion"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+9).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["pro0009"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+10).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["pro0010"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+11).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["precioe"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+12).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["pro0022"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+13).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["pro0023"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+14).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["pro0027"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+15).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["pro0028"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+16).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["pro0019"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+17).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["pro0020"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+18).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvp0001"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+19).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvp0002"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+20).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvp0003"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+21).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvp0004"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+22).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvp0005"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+23).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvp0006"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+25).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["cpnic_p"])); 
    
                                        $fila=$fila+1;
    
                                    } 
                                }


                            $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_IND_Capitulo9.sav','MER0001',$formulario[0]["nombre"]);
                            if ($resultposicion){
                            $hojagrupo = $resultposicion[0]["hoja"]; 
                            $posiciongrupo = $resultposicion[0]["posicion"];
                            $sheet =$spreadsheet->getSheetByName($hojagrupo);
                        
                            $fila =  $inscarga->limpiarletras($posiciongrupo);
                            $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_IND_Capitulo9.sav',$formulario[0]["nombre"]);
                            $totalfilas=count($resultposicion);
                            $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                          
                                for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                    $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0003"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0004"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0005"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+4).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0006"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+5).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0016"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+6).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0017"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+7).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0021"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+8).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0010"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+9).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0011"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+10).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0018"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+11).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0013"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+12).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+13).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+14).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0003"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+15).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0004"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+16).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0005"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+17).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0006"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+18).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+19).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+20).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0003"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+21).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0004"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+22).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0005"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+23).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0006"]));
                                    
                                    $fila=$fila+1;

                                } 
                            }

                               $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_IND_Capitulo7.sav','SIN0001',$formulario[0]["nombre"]);
                            if ($resultposicion){
                        $hojagrupo = $resultposicion[0]["hoja"]; 
                        $posiciongrupo = $resultposicion[0]["posicion"];
                        $sheet =$spreadsheet->getSheetByName($hojagrupo);
                    
                        $fila =  $inscarga->limpiarletras($posiciongrupo);
                        $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_IND_Capitulo7.sav',$formulario[0]["nombre"]);
                        $totalfilas=count($resultposicion);
                        $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                      
                            for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["sin0001"]));
                                $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["sin0003"]));
                                $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["sin0006"]));                                        
                                $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["sin0007"]));



                                $fila=$fila+1;

                            } 
                        }
     
                                    $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_IND_Capitulo10.sav','MPU0001',$formulario[0]["nombre"]);
                                    if ($resultposicion){
                                $hojagrupo = $resultposicion[0]["hoja"]; 
                                $posiciongrupo = $resultposicion[0]["posicion"];
                                $sheet =$spreadsheet->getSheetByName($hojagrupo);
                            
                                $fila =  $inscarga->limpiarletras($posiciongrupo);
                                $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_IND_Capitulo10.sav',$formulario[0]["nombre"]);
                                $totalfilas=count($resultposicion);
                                $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                              
                                    for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) {                                       
                                        $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0001"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0002"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0003"]));                                        
                                        $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0004"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+4).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0005"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+5).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0006"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+6).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0007"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+7).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["precioin"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+8).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0008"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+9).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0009"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+10).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["precioie"])); 
                                        $sheet->setCellValue(columnaALetra($columnaNumero+11).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0024"])); 
                                        $sheet->setCellValue(columnaALetra($columnaNumero+12).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0025"]));                                         
                                        $sheet->setCellValue(columnaALetra($columnaNumero+13).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0026"])); 
                                        $sheet->setCellValue(columnaALetra($columnaNumero+14).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0027"])); 
                                        $sheet->setCellValue(columnaALetra($columnaNumero+15).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0038"])); 
                                        $sheet->setCellValue(columnaALetra($columnaNumero+16).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0039"])); 
                                        $sheet->setCellValue(columnaALetra($columnaNumero+17).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0016"])); 
                                        $sheet->setCellValue(columnaALetra($columnaNumero+18).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0017"])); 
                                        $sheet->setCellValue(columnaALetra($columnaNumero+19).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0028"])); 
                                        $sheet->setCellValue(columnaALetra($columnaNumero+20).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0029"])); 
                                        $sheet->setCellValue(columnaALetra($columnaNumero+21).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0032"])); 
                                        $sheet->setCellValue(columnaALetra($columnaNumero+22).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0033"])); 
                                        $sheet->setCellValue(columnaALetra($columnaNumero+23).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0018"])); 
                                        $sheet->setCellValue(columnaALetra($columnaNumero+24).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0019"])); 
                                        $sheet->setCellValue(columnaALetra($columnaNumero+25).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0020"])); 
                                        $sheet->setCellValue(columnaALetra($columnaNumero+26).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0021"])); 
                                        $sheet->setCellValue(columnaALetra($columnaNumero+28).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["cpnic_mp"]));     
    
                                        $fila=$fila+1;
    
                                    } 
                                }


                              

                            $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('E_AIF_IND_Anexo2_D.sav','OSP0001',$formulario[0]["nombre"]);
                            if ($resultposicion){
                        $hojagrupo = $resultposicion[0]["hoja"]; 
                        $posiciongrupo = $resultposicion[0]["posicion"];
                        $sheet =$spreadsheet->getSheetByName($hojagrupo);
                    
                        $fila =  $inscarga->limpiarletras($posiciongrupo);
                        $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'E_AIF_IND_Anexo2_D.sav',$formulario[0]["nombre"]);
                        $totalfilas=count($resultposicion);
                        $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                      
                            for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0001"]));
                                $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0002"]));
                                $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0003"]));                                        
                                $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0004"]));



                                $fila=$fila+1;

                            } 
                        }




                    $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('E_AIF_IND_Anexo3_D.sav','OGT0001',$formulario[0]["nombre"]);
                    if ($resultposicion){
                $hojagrupo = $resultposicion[0]["hoja"]; 
                $posiciongrupo = $resultposicion[0]["posicion"];
                $sheet =$spreadsheet->getSheetByName($hojagrupo);
            
                $fila =  $inscarga->limpiarletras($posiciongrupo);
                $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'E_AIF_IND_Anexo3_D.sav',$formulario[0]["nombre"]);
                $totalfilas=count($resultposicion);
                $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
              
                    for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                        $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0003"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0004"]));



                        $fila=$fila+1;

                    } 
                }

                       $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_IND_Capitulo11.sav','MEI0001',$formulario[0]["nombre"]);
                                    if ($resultposicion){
                                $hojagrupo = $resultposicion[0]["hoja"]; 
                                $posiciongrupo = $resultposicion[0]["posicion"];
                                $sheet =$spreadsheet->getSheetByName($hojagrupo);
                            
                                $fila =  $inscarga->limpiarletras($posiciongrupo);
                                $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_IND_Capitulo11.sav',$formulario[0]["nombre"]);
                                $totalfilas=count($resultposicion);
                                $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                              
                                    for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) {         
                                            $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mei0001"]));
                                            $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mei0002"]));
                                            $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mei0003"]));                                        
                                            $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mei0004"]));
                                            $sheet->setCellValue(columnaALetra($columnaNumero+4).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mei0005"]));
                                            $sheet->setCellValue(columnaALetra($columnaNumero+5).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mei0006"]));
                                            $sheet->setCellValue(columnaALetra($columnaNumero+6).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mei0007"]));
                                            $sheet->setCellValue(columnaALetra($columnaNumero+7).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["precioen"]));
                                            $sheet->setCellValue(columnaALetra($columnaNumero+8).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mei0008"]));
                                            $sheet->setCellValue(columnaALetra($columnaNumero+9).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mei0009"]));
                                            $sheet->setCellValue(columnaALetra($columnaNumero+10).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["precioee"])); 
                                            $sheet->setCellValue(columnaALetra($columnaNumero+11).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mei0024"])); 
                                            $sheet->setCellValue(columnaALetra($columnaNumero+12).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mei0025"]));                                         
                                            $sheet->setCellValue(columnaALetra($columnaNumero+13).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mei0026"])); 
                                            $sheet->setCellValue(columnaALetra($columnaNumero+14).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mei0027"])); 
                                            $sheet->setCellValue(columnaALetra($columnaNumero+15).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mei0038"])); 
                                            $sheet->setCellValue(columnaALetra($columnaNumero+16).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mei0039"])); 
                                            $sheet->setCellValue(columnaALetra($columnaNumero+17).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mei0016"])); 
                                            $sheet->setCellValue(columnaALetra($columnaNumero+18).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mei0017"])); 
                                            $sheet->setCellValue(columnaALetra($columnaNumero+19).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mei0028"])); 
                                            $sheet->setCellValue(columnaALetra($columnaNumero+20).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mei0029"])); 
                                            $sheet->setCellValue(columnaALetra($columnaNumero+21).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mei0032"])); 
                                            $sheet->setCellValue(columnaALetra($columnaNumero+22).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mei0033"])); 
                                            $sheet->setCellValue(columnaALetra($columnaNumero+23).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mei0018"])); 
                                            $sheet->setCellValue(columnaALetra($columnaNumero+24).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mei0019"])); 
                                            $sheet->setCellValue(columnaALetra($columnaNumero+25).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mei0020"])); 
                                            $sheet->setCellValue(columnaALetra($columnaNumero+26).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mei0021"])); 
                                            $sheet->setCellValue(columnaALetra($columnaNumero+28).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["cpnic_me"]));     
    
                                        $fila=$fila+1;
    
                                    } 
                                }

 
        }


        function detallescomercio($spreadsheet,$boleta,$formulario){
            $inscarga = new generarController();
           //iniciamos con los detalles
                            $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_CO_Capitulo7_1.sav','MER0001',$formulario[0]["nombre"]);
                            if ($resultposicion){
                            $hojagrupo = $resultposicion[0]["hoja"]; 
                            $posiciongrupo = $resultposicion[0]["posicion"];
                            $sheet =$spreadsheet->getSheetByName($hojagrupo);
                        
                            $fila =  $inscarga->limpiarletras($posiciongrupo);
                            $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_CO_Capitulo7_1.sav',$formulario[0]["nombre"]);
                            $totalfilas=count($resultposicion);
                            $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                          
                                for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                    $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0003"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0004"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+4).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0005"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+5).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0006"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+6).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0016"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+7).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0017"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+8).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0021"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+9).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0010"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+10).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0011"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+11).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0018"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+12).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0013"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+13).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0014"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+14).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0015"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+21).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+22).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+23).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0003"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+24).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0004"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+25).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0005"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+26).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0006"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+27).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+28).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+29).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0003"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+30).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0004"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+31).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0005"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+32).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0006"]));


                                    $fila=$fila+1;

                                } 
                            }

                                $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_CO_Capitulo7_1_1.sav','MER0001',$formulario[0]["nombre"]);
                                if ($resultposicion){
                                $hojagrupo = $resultposicion[0]["hoja"]; 
                                $posiciongrupo = $resultposicion[0]["posicion"];
                                $sheet =$spreadsheet->getSheetByName($hojagrupo);
                            
                                $fila =  $inscarga->limpiarletras($posiciongrupo);
                                $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_CO_Capitulo7_1_1.sav',$formulario[0]["nombre"]);
                                $totalfilas=count($resultposicion);
                                $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                              
                                    for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                        $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0001"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0002"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0003"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0004"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+4).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0005"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+5).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0006"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+6).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0016"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+7).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0017"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+8).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0021"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+9).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0010"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+10).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0011"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+11).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0018"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+12).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0013"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+13).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0014"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+14).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0015"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+21).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0001"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+22).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0002"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+23).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0003"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+24).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0004"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+25).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0005"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+26).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0006"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+27).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0001"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+28).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0002"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+29).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0003"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+30).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0004"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+31).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0005"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+32).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0006"]));
    
    
                                        $fila=$fila+1;
    
                                    } 
                                }

                                $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_CO_Capitulo7_2.sav','MER0001',$formulario[0]["nombre"]);
                                if ($resultposicion){
                                $hojagrupo = $resultposicion[0]["hoja"]; 
                                $posiciongrupo = $resultposicion[0]["posicion"];
                                $sheet =$spreadsheet->getSheetByName($hojagrupo);
                            
                                $fila =  $inscarga->limpiarletras($posiciongrupo);
                                $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_CO_Capitulo7_2.sav',$formulario[0]["nombre"]);
                                $totalfilas=count($resultposicion);
                                $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                              
                                    for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                        $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0001"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0002"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0003"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0004"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+4).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0005"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+5).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0006"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+6).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0016"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+7).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0017"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+8).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0021"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+9).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0010"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+10).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0011"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+11).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0018"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+12).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0013"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+13).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0014"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+14).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mer0015"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+21).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0001"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+22).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0002"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+23).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0003"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+24).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0004"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+25).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0005"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+26).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvt0006"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+27).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0001"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+28).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0002"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+29).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0003"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+30).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0004"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+31).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0005"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+32).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mct0006"]));
    
    
                                        $fila=$fila+1;
    
                                    } }

                                    $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_CO_Capitulo6.sav','MPU0001',$formulario[0]["nombre"]);
                                    if ($resultposicion){
                                $hojagrupo = $resultposicion[0]["hoja"]; 
                                $posiciongrupo = $resultposicion[0]["posicion"];
                                $sheet =$spreadsheet->getSheetByName($hojagrupo);
                            
                                $fila =  $inscarga->limpiarletras($posiciongrupo);
                                $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_CO_Capitulo6.sav',$formulario[0]["nombre"]);
                                $totalfilas=count($resultposicion);
                                $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                              
                                    for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                        $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0001"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0002"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0003"]));                                        
                                        $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0005"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+4).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0007"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+5).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0009"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+6).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0025"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+7).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0027"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+8).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0039"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+9).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0017"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+10).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0029"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+11).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0033"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+12).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0019"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+13).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mpu0021"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+17).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvti0001"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+18).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvti0002"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+19).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvti0003"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+20).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvti0004"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+21).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvti0005"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+22).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvti0006"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+23).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0001"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+24).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0002"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+25).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0003"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+26).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0004"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+27).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0005"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+28).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mcit0006"]));
    
    
                                        $fila=$fila+1;
    
                                    } 
                                }

                                    
                                    $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_CO_Capitulo8.sav','PRO0001',$formulario[0]["nombre"]);
                                    if ($resultposicion){
                                $hojagrupo = $resultposicion[0]["hoja"]; 
                                $posiciongrupo = $resultposicion[0]["posicion"];
                                $sheet =$spreadsheet->getSheetByName($hojagrupo);
                            
                                $fila =  $inscarga->limpiarletras($posiciongrupo);
                                $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_CO_Capitulo8.sav',$formulario[0]["nombre"]);
                                $totalfilas=count($resultposicion);
                                $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                              
                                    for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                        $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["pro0001"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["pro0002"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["pro0003"]));                                        
                                        $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["pro0005"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+4).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["pro0006"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+5).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["pro0008"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+6).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["pro0010"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+7).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["pro0023"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+8).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["pro0028"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+9).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["pro0020"]));
                                        $sheet->setCellValue(columnaALetra($columnaNumero+12).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvp0001"])); 
                                        $sheet->setCellValue(columnaALetra($columnaNumero+13).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvp0002"])); 
                                        $sheet->setCellValue(columnaALetra($columnaNumero+14).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvp0003"])); 
                                        $sheet->setCellValue(columnaALetra($columnaNumero+15).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvp0004"])); 
                                        $sheet->setCellValue(columnaALetra($columnaNumero+16).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvp0005"])); 
                                        $sheet->setCellValue(columnaALetra($columnaNumero+17).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["mvp0006"])); 

    
    
                                        $fila=$fila+1;
    
                                    } 
                                }


                                $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_CO_Anexo2_D.sav','OSP0001',$formulario[0]["nombre"]);
                                if ($resultposicion){
                            $hojagrupo = $resultposicion[0]["hoja"]; 
                            $posiciongrupo = $resultposicion[0]["posicion"];
                            $sheet =$spreadsheet->getSheetByName($hojagrupo);
                        
                            $fila =  $inscarga->limpiarletras($posiciongrupo);
                            $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_CO_Anexo2_D.sav',$formulario[0]["nombre"]);
                            $totalfilas=count($resultposicion);
                            $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                          
                                for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                    $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0001"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0002"]));
                                    $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0003"]));                                        
                                    $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0004"]));



                                    $fila=$fila+1;

                                } 
                            }

                            $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('E_AIF_CO_Anexo2_D.sav','OSP0001',$formulario[0]["nombre"]);
                            if ($resultposicion){
                        $hojagrupo = $resultposicion[0]["hoja"]; 
                        $posiciongrupo = $resultposicion[0]["posicion"];
                        $sheet =$spreadsheet->getSheetByName($hojagrupo);
                    
                        $fila =  $inscarga->limpiarletras($posiciongrupo);
                        $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'E_AIF_CO_Anexo2_D.sav',$formulario[0]["nombre"]);
                        $totalfilas=count($resultposicion);
                        $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                      
                            for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                                $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0001"]));
                                $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0002"]));
                                $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0003"]));                                        
                                $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["osp0004"]));



                                $fila=$fila+1;

                            } 
                        }


                        $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('AIF_CO_Anexo3_D.sav','OGT0001',$formulario[0]["nombre"]);
                        if ($resultposicion){
                    $hojagrupo = $resultposicion[0]["hoja"]; 
                    $posiciongrupo = $resultposicion[0]["posicion"];
                    $sheet =$spreadsheet->getSheetByName($hojagrupo);
                
                    $fila =  $inscarga->limpiarletras($posiciongrupo);
                    $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'AIF_CO_Anexo3_D.sav',$formulario[0]["nombre"]);
                    $totalfilas=count($resultposicion);
                    $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
                  
                        for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                            $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0003"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0004"]));



                            $fila=$fila+1;

                        } 
                    }

                    $resultposicion =$inscarga->OBTENERPOSICIONDETALLE('E_AIF_CO_Anexo3_D.sav','OGT0001',$formulario[0]["nombre"]);
                    if ($resultposicion){
                $hojagrupo = $resultposicion[0]["hoja"]; 
                $posiciongrupo = $resultposicion[0]["posicion"];
                $sheet =$spreadsheet->getSheetByName($hojagrupo);
            
                $fila =  $inscarga->limpiarletras($posiciongrupo);
                $resultposicion =$inscarga->OBTENERAAGRUPACION($boleta,'E_AIF_CO_Anexo3_D.sav',$formulario[0]["nombre"]);
                $totalfilas=count($resultposicion);
                $columnaNumero = Coordinate::columnIndexFromString($inscarga->limpiarnumeros($posiciongrupo)); 
              
                    for ($colbol = 0; $colbol <= $totalfilas-1; ++$colbol) { 
                        $sheet->setCellValue(columnaALetra($columnaNumero+0).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0001"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+1).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0002"]));
                            $sheet->setCellValue(columnaALetra($columnaNumero+2).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0003"]));                                        
                            $sheet->setCellValue(columnaALetra($columnaNumero+3).$fila, reemplazarCaracteresEspeciales($resultposicion[$colbol]["ogt0004"]));



                        $fila=$fila+1;

                    } 
                }
 
        }