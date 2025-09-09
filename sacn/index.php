<?php
error_reporting(0);
// ini_set('display_errors', 1);
// ini_set('log_errors', 1);
// ini_set('error_log', '/var/log/php_errors.log');
    require_once "./config/app.php";
    require_once "./autoload.php";

    /*---------- Iniciando sesion ----------*/
    require_once "./app/views/inc/session_start.php";

    if(isset($_GET['views'])){
          
        $url=explode("/", $_GET['views']);
    }else{
        $url=["login"];
    }

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php require_once "./app/views/inc/head.php"; ?>
</head>

<body onload="mueveReloj()">
    <?php
        use app\controllers\viewsController;
        use app\controllers\loginController;

        $insLogin = new loginController();

        $viewsController= new viewsController();
        $vista=$viewsController->obtenerVistasControlador($url[0]);

        if($vista=="login" || $vista=="404" || $vista=="sinpermiso"){
            require_once "./app/views/content/".$vista."-view.php";
            
        }else{
    ?>
    <main class="page-container">
        <?php
            # Cerrar sesion #
            if((!isset($_SESSION['id']) || $_SESSION['id']=="") || (!isset($_SESSION['usuario']) || $_SESSION['usuario']=="")){
                $insLogin->cerrarSesionControlador();
                exit();
            }
           
            require_once "./app/views/inc/navlateraldin.php";
    ?>
        <section class="full-width pageContent scroll" id="pageContent">
            <?php
                require "./app/views/inc/navbar.php";

                require $vista;
            ?>
        </section>
    </main>
    <?php
        }

        require_once "./app/views/inc/script.php"; 
    ?>
</body>

</html>
