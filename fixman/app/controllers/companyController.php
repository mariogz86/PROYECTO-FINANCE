<?php

	namespace app\controllers;
	use app\models\mainModel;
	if(isset($_POST['modulo_Opcion'])){
		require_once '../phpmailer/src/PHPMailer.php';
		require_once '../phpmailer/src/SMTP.php';
		require_once '../phpmailer/src/Exception.php';
	}

	use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

	//delcaracion de funciones del controlador
	class companyController extends mainModel{
		//funcion para cargar los formularios 
        public function listarformulario(){
			
			$consulta_datos="select * from \"SYSTEM\".OBTENER_COMPANY;"; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}
		
			//funcion para cargar los formularios 
			public function Buscarusuario(){
				if ($_POST["idCompany"]=="0"){
					$consulta_datos="select count(*) from \"SYSTEM\".OBTENER_COMPANY where UPPER(nombre)=UPPER('".$_POST["nombre"]."');"; 
				}
				else{
					$consulta_datos="select count(*) from \"SYSTEM\".OBTENER_COMPANY where UPPER(nombre)=UPPER('".$_POST["nombre"]."') and id_company not in ('".$_POST["idCompany"]."');"; 
				}
				
	
				$datos = $this->ContarRegistros($consulta_datos); 
	
				return $datos;
			}
	 
		//Funcion para guardar los datos del formulario
		public function guardar(){
			  
			$datosform[] = array(
				'idCompany' => $_POST["idCompany"],
				'nombre' => $_POST["nombre"],
				'ciudad' => $_POST["ciudad"],
				'direccion' => $_POST["direccion"],
				'cmb_estado' => $_POST["cmb_estado"],
				'codigozip' => $_POST["codigozip"],   
				'email' => $_POST["email"],
				'nombrecompleto' => $_POST["nombrecompleto"],
				'telefono' => $_POST["telefono"],
				'credito' => $_POST["credito"],
				'nte' => $_POST["nte"],
			);

			$datos=json_encode( $datosform);


			if ($_POST["idCompany"]=="0"){
				$sentencia ="select \"SYSTEM\".INSERTAR_COMPANY('".$datos."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
 				$sql->execute();
				$total = (int) $sql->fetchColumn();  
					
			}else{
				$sentencia ="select \"SYSTEM\".ACTUALIZAR_COMPANY('".$datos."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute();	
				$total = (int) $sql->fetchColumn(); 

			} 

		 return $total;
		}

	 
		//Funcion para cambiar de estado al catalogo 
		public function cambiarestado($estado){ 

			$idcat = $_POST["id_company"];
				$sentencia ="select \"SYSTEM\".cambiarestado_company('".$idcat."','".$estado."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute(); 

		 return $sql;
		}

		
 
		}