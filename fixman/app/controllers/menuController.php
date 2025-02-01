<?php

	namespace app\controllers;
	use app\models\mainModel;
	//delcaracion de funciones para el controlador
	class menuController extends mainModel{
		//funcion para obtener los rols de la base de datos
        public function listarmenu(){
			$consulta_datos="select * from \"SYSTEM\".OBTENER_MENU;"; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}
		//Funcion para guardar los datos del rol
		public function guardar(){
			 
			
			$hoy = date("Y-m-d");
			$nombre = trim($_POST["menu"]);					
			$orden = trim($_POST["orden"]);
			$icono = trim($_POST["icono"]);

			if ($_POST["idmenu"]=="0"){
				$sentencia ="select \"SYSTEM\".insertar_menu('".$nombre."','".$icono."','".$orden."','0','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute();
			}else{				
				$sentencia ="select \"SYSTEM\".actualizar_menu('".$_POST["idmenu"]."','".$nombre."','".$icono."','".$orden."','0','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute();	

			} 

		 return $sql;
		}

        //Funcion para validar que no exista el nombre del menu
        public function Buscarmenu(){
                    
            $nombre = trim($_POST["menu"]);			

            if ($_POST["idmenu"]=="0"){
                $sentencia ="select * from \"SYSTEM\".OBTENER_MENU where UPPER(nombre)=UPPER('".$nombre."');  ";
                $datos = $this->ejecutarConsulta($sentencia);
                $datos = $datos->fetchAll();
            }else{				
                $sentencia ="select * from \"SYSTEM\".OBTENER_MENU where UPPER(nombre)=UPPER('".$nombre."') and id_menu not in ('".$_POST["idmenu"]."');  ";
                $datos = $this->ejecutarConsulta($sentencia);
			$datos = $datos->fetchAll();

            } 

        return $datos;
        }

        //Funcion para validar que el menu no este asociado a un rol
        public function validar_inactivar(){
          
           			
            $sentencia ="select * from \"SYSTEM\".OBTENER_ROLMENU where id_menu='".$_POST["id_menu"]."';  ";
            $datos = $this->ejecutarConsulta($sentencia);
			$datos = $datos->fetchAll();
 

        return $datos;
        }

		//Funcion para cambiar de estado al rol 
		public function cambiarestado($estado){ 

			$idcat = $_POST["id_menu"];
				$sentencia ="select \"SYSTEM\".CAMBIARESTADO_MENU('".$idcat."','".$estado."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute(); 

		 return $sql;
		}

    }