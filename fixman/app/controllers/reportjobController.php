<?php

namespace app\controllers;

use app\models\mainModel;

//delcaracion de funciones del controlador
class reportjobController extends mainModel
{
    //funcion para cargar los formularios 
    public function listar()
    {
         
    $consulta_datos = "select * from \"SYSTEM\".reportejobs   ;";
       


        $datos = $this->ejecutarConsulta($consulta_datos);
        $datos = $datos->fetchAll();

        return $datos;
    }


    public function listarfechas()
    {
         
    $consulta_datos = "select * from \"SYSTEM\".reportejobs  WHERE fecha_creacion BETWEEN '" . $_GET['cargagridfecha'] . "' AND '" . $_GET['fechafin'] . "'; ;";
       


        $datos = $this->ejecutarConsulta($consulta_datos);
        $datos = $datos->fetchAll();

        return $datos;
    }
}