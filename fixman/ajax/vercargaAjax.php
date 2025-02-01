<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
require_once "../autoload.php";
 
 
 
//incializar controladores
use app\controllers\vercargaController;
$inscarga = new vercargaController();

use app\controllers\FuncionesController;
$funciones = new FuncionesController();

    //Metodo POST para el guardado de los registros
    if(isset($_POST['modulo_Opcion'])=="vercarga")
	{
        $cmb_formulario=$_POST['cmb_formulario'];
        $cmb_archivofuente=$_POST['cmb_archivofuente'];
 
        $formulario =$inscarga->obtenerarchivosfuente_por_idformulario($cmb_formulario,$cmb_archivofuente);
       
        if($formulario){
            $alerta=[
                "tipo"=>"limpiar",
                "titulo"=>"Carga de datos",
                "texto"=>"Los datos se cargaron correctamente.", 
                "icono"=>"success",    
                "datos"=>$formulario                 
            ];
        }else{
            $alerta=[
                "tipo"=>"limpiar",
                "titulo"=>"Carga de datos",
                "texto"=>"No se encontro información.", 
                "icono"=>"warning",    
                "datos"=>"[]"                 
            ];
        }
        
        echo json_encode( $alerta); 
    }

 