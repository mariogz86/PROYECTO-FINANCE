<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php"; 
require_once "../autoload.php";

//Metodo POST para cargar  las validaciones aplicadas al formulario
if (isset($_GET['error'])) {
    $log_message = "[" . date("Y-m-d H:i:s") . "] [".$_GET['error']."]\n\n"; 
        error_log( $log_message  , 3, '../Error/log.txt');
}