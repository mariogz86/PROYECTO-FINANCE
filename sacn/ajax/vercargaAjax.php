<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
require_once "../autoload.php";
 
 
 
//incializar controladores
use app\controllers\vercargaController;
$inscarga = new vercargaController();

use app\controllers\FuncionesController;
$funciones = new FuncionesController();
use app\controllers\logerrorController;
$log = new logerrorController();



    //Metodo POST para el guardado de los registros
    if(isset($_POST['modulo_Opcion'])=="vercarga")
	{
        try {
        $cmb_formulario=$_POST['cmb_formulario'];
        $cmb_archivofuente=$_POST['cmb_archivofuente'];
        $esdetalle=1;

        $formulario=$inscarga->obtenercargadetalle($cmb_formulario,$cmb_archivofuente);

        if(Empty($formulario[0]["datosjson"])){
            $esdetalle=0;
            $formulario =$inscarga->obtenerarchivosfuente_por_idformulario($cmb_formulario,$cmb_archivofuente);
        }else{
            $formulario=$formulario[0]["datosjson"];
        }
 
        
       
        if($formulario){
            $alerta=[
                "tipo"=>"limpiar",
                "titulo"=>"Carga de datos",
                "texto"=>"Los datos se cargaron correctamente.", 
                "icono"=>"success",    
                "datos"=>$formulario,
                "esdetalle"=>$esdetalle                      
            ];
        }else{
            $alerta=[
                "tipo"=>"limpiar",
                "titulo"=>"Carga de datos",
                "texto"=>"No se encontro información.", 
                "icono"=>"warning",    
                "datos"=>"[]"     ,
                "esdetalle"=>$esdetalle            
            ];
        }
            }
    catch (Exception  $e) {
       
            $log->guardarlog($e->getMessage());	
            $alerta=[
                "tipo"=>"limpiar",
                "titulo"=>"Carga de datos",
                "texto"=>"Consulte con el administrador", 
                "icono"=>"error",    
                "datos"=>"[]"     ,
                "esdetalle"=>$esdetalle            
            ];
    }

        echo json_encode( $alerta); 
    }

 
 