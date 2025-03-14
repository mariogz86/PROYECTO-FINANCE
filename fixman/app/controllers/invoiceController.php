<?php

namespace app\controllers;

use app\models\mainModel;

if (isset($_POST['modulo_Opcion'])) {
    require_once '../phpmailer/src/PHPMailer.php';
    require_once '../phpmailer/src/SMTP.php';
    require_once '../phpmailer/src/Exception.php';
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//delcaracion de funciones del controlador
class invoiceController extends mainModel
{
    //funcion para cargar los formularios 
    public function listar()
    {
        if ($_SESSION['rol'] != "Administrator") {
            $consulta_datos = "select * from \"SYSTEM\".OBTENER_JOBS where id_tecnico='" . $_SESSION['id'] . "' and estadojob in('Diagnosed','complete','Completed Bookind Date')";
        } else {
            $consulta_datos = "select * from \"SYSTEM\".OBTENER_JOBS  where estadojob in('Diagnosed','complete','Completed Bookind Date');";
        }


        $datos = $this->ejecutarConsulta($consulta_datos);
        $datos = $datos->fetchAll();

        return $datos;
    }


    public function obtenerjobporid($idtrabajo)
    {
        $consulta_datos = "select * from \"SYSTEM\".OBTENER_JOBS where id_trabajo='" . $idtrabajo . "'";
        $datos = $this->ejecutarConsulta($consulta_datos);
        $datos = $datos->fetchAll();

        return $datos;
    }

    public function obtenerserviciosporidtrabajo($idtrabajo)
    {
        $consulta_datos = "select * from \"SYSTEM\".OBTENER_SERVICE where id_trabajo='" . $idtrabajo . "'";
        $datos = $this->ejecutarConsulta($consulta_datos);
        $datos = $datos->fetchAll();

        return $datos;
    }

    public function obtenerdiagnosticos($idtrabajo)
    {
        $consulta_datos = "select s.appliance,d.serial,d.laborfee,d.nota from \"SYSTEM\".obtener_service  s
                        inner join \"SYSTEM\".diagnostico d on d.id_servicio=s.id_servicio 
                        where s.id_trabajo='" . $idtrabajo . "'";
        $datos = $this->ejecutarConsulta($consulta_datos);
        $datos = $datos->fetchAll();

        return $datos;
    }

    public function obtenerpartes($idtrabajo)
    {
        $consulta_datos = "select s.appliance,cp.nombre,d.serial,d.cantidad,d.costo,(d.cantidad * d.costo) as amount from \"SYSTEM\".obtener_service  s
                            inner join \"SYSTEM\".parte d on d.id_servicio=s.id_servicio 
                            inner join \"SYSTEM\".catalogovalor cp on cp.id_catalogovalor=d.id_valorparte
                        where s.id_trabajo='" . $idtrabajo . "'";
        $datos = $this->ejecutarConsulta($consulta_datos);
        $datos = $datos->fetchAll();

        return $datos;
    }
}
