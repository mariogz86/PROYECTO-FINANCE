<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
//require_once "../autoload.php";
require_once '../PhpOffice/autoload.php';
 
 

 
//incializar controlador y libreria en caso de utilizarse
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	use PhpOffice\PhpSpreadsheet\IOFactory;
 
 
 
//incializar controladores
use app\controllers\generarController;
$inscarga = new generarController();

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


        //Metodo POST para guardar los registros
        if(isset($_POST['modulo_Opcion'])=="generarplantilla")
        {

            $formulario =$inscarga->obtenerdatos_formulario($_POST["cmb_formulario"]);
            if (!is_dir( $_POST["ruta"])) {
                $alerta=[
                    "tipo"=>"simple",
                    "titulo"=>"Error",
                    "texto"=>"Ruta indicada no existe, por favor validar.",
                    "icono"=>"error"
                ];
            }else{
                ini_set('max_execution_time', '0'); 
                set_time_limit(0);
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

                         if ($archivo=="AIF_CO_Anexo3_D.sav") {
                            $celda2=(string)$letras.(string)$numeroposicion;
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
					 $writer->save($_POST["ruta"]."/".$_POST["nombre"].".xlsx");
            
 

                if($result){
                        $alerta=[
                            "tipo"=>"limpiar",
                            "titulo"=>"Generar Plantilla",
                            "texto"=>"Se genero el archivo correctamente",
                            "icono"=>"success"
                        ];	
                    }else{
                        $alerta=[
                            "tipo"=>"simple",
                        "titulo"=>"Error",
                        "texto"=>"No se pudo generar el archivo.",
                        "icono"=>"error"
                        ];	
                    }
            }
    
            echo json_encode($alerta); 
             
        }


       