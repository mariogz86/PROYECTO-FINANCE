<?php

	namespace app\controllers;
	use app\models\mainModel;
	//delcaracion de funciones para el controlador
	class rolController extends mainModel{
		//funcion para obtener los rols de la base de datos
        public function listarrol(){
			$consulta_datos="select * from \"SYSTEM\".OBTENER_ROLES;"; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}
		//Funcion para guardar los datos del rol
		public function guardar(){
			 
			
			$hoy = date("Y-m-d");
			$nombre = $this->limpiarCadena(trim($_POST["rol"]));					
			$descripcion = $this->limpiarCadena(trim($_POST["descripcion"]));

			if ($_POST["idrol"]=="0"){
				$sentencia ="select \"SYSTEM\".insertar_rol('".$nombre."','".$descripcion."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute();
			}else{				
				$sentencia ="select \"SYSTEM\".actualizar_rol('".$_POST["idrol"]."','".$nombre."','".$descripcion."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute();	

			} 

		 return $sql;
		}

        //Funcion para validar que no exista el nombre del rol
        public function BuscarRol(){
                    
            $nombre = trim($_POST["rol"]);			

            if ($_POST["idrol"]=="0"){
                $sentencia ="select * from \"SYSTEM\".OBTENER_ROLES where UPPER(rol)=UPPER('".$nombre."');  ";
                $datos = $this->ejecutarConsulta($sentencia);
                $datos = $datos->fetchAll();
            }else{				
                $sentencia ="select * from \"SYSTEM\".OBTENER_ROLES where UPPER(rol)=UPPER('".$nombre."') and id_rol not in ('".$_POST["idrol"]."');  ";
                $datos = $this->ejecutarConsulta($sentencia);
			$datos = $datos->fetchAll();

            } 

        return $datos;
        }

        //Funcion para validar que el rol no este asociado a un usuario
        public function validar_inactivar(){
          
           			
            $sentencia ="select * from \"SYSTEM\".OBTENER_USUARIOS where id_rol='".$_POST["id_rol"]."';  ";
            $datos = $this->ejecutarConsulta($sentencia);
			$datos = $datos->fetchAll();
 

        return $datos;
        }

		//Funcion para cambiar de estado al rol 
		public function cambiarestado($estado){ 

			$idcat = $_POST["id_rol"];
				$sentencia ="select \"SYSTEM\".cambiarestado_rol('".$idcat."','".$estado."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute(); 

		 return $sql;
		}

    }