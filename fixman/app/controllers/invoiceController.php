<?php

namespace app\controllers;

use app\models\mainModel;

if (isset($_POST['enviarfactura'])) {
    require_once '../phpmailer/src/PHPMailer.php';
    require_once '../phpmailer/src/SMTP.php';
    require_once '../phpmailer/src/Exception.php';
}
    
    if (isset($_POST['enviarreporte'])) {
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

     public function obtenerdisclaimer($codigo)
    {
        $consulta_datos = "select * from \"SYSTEM\".catalogo where codigo='" . $codigo . "'";
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

      public function obtenerreporteserviciosporidtrabajo($idtrabajo)
    {
        $consulta_datos = "select * from \"SYSTEM\".OBTENER_REPORTESERVICE where id_trabajo='" . $idtrabajo . "'";
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

    public function usuariocreado($correo,$numref, $pdf)
	{
		$phpmailer = new PHPMailer(true);
		$phpmailer->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true,
			)
		);
		$phpmailer->isSMTP();
		$phpmailer->Host       = 'smtp-relay.sendinblue.com';
		$phpmailer->SMTPAuth   = true;
		$phpmailer->Username   = '854a84002@smtp-brevo.com';  // Tu correo registrado en Sendinblue
		$phpmailer->Password   = '7DJzMZct3NVwC8X1';  // API Key proporcionada por Sendinblue
		$phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		$phpmailer->Port       = 587;
		$phpmailer->SMTPDebug = APP_SMTPDebug;

	 

		$phpmailer->setFrom(APP_Username);
		$phpmailer->addAddress($correo);

        // Adjuntar el PDF desde la variable
        $phpmailer->addStringAttachment($pdf, $numref.'.pdf', 'base64', 'application/pdf');
                

		$phpmailer->isHTML(true); // Set email format to plain text

		$phpmailer->Subject = "Invoice sending";
        $phpmailer->Body    = '<p>Greetings, attached is the invoice for the services performed on your behalf.</p>';

		if (!$phpmailer->send()) {
			echo  'Error en el envío de correo electrónico, Error: ' . $phpmailer->ErrorInfo .
				' Para mayor información pongase en contacto con el administrador del sistema';
		}else{
            return 1;
        }
	}
}
