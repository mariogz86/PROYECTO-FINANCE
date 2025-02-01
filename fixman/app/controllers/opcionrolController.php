<?php

	namespace app\controllers;
	use app\models\mainModel;
	//delcaracion de funciones para el controlador
	class opcionrolController extends mainModel{
		//funcion para obtener los rols de la base de datos
        public function listar($idrol){
            if($idrol==0){
                $consulta_datos="select * from \"SYSTEM\".OBTENER_ROLOPCION;"; 
            }else{
                $consulta_datos="select * from \"SYSTEM\".OBTENER_ROLOPCION where id_rol='".$idrol."';"; 
            }

			

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}
		//Funcion para guardar los datos del rol
		public function guardar(){
			 
			
			$idrol = trim($_POST["cmb_rol"]);			
			$opcion = trim($_POST["cmb_opcion"]);

			 
				$sentencia ="select \"SYSTEM\".INSERTAR_ROLOPCION('".$opcion."','".$idrol."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute();
			 

		 return $sql;
		}

		public function guardarrolmenu($menu,$rol){
		 

			 
				$sentencia ="select \"SYSTEM\".INSERTAR_ROLMENU('".$menu."','".$rol."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute();
			 

		 return $sql;
		}

        //Funcion para validar que no exista el nombre del menu
        public function Buscaropcion(){
                    
            $idrol = trim($_POST["cmb_rol"]);			
			$opcion = trim($_POST["cmb_opcion"]);
             
                $sentencia ="select * from \"SYSTEM\".OBTENER_rolopcion where id_rol='".$idrol."' and id_opcion='".$opcion."'  ";
                $datos = $this->ejecutarConsulta($sentencia);
                $datos = $datos->fetchAll();
           

        return $datos;
        }

		 //Funcion para validar que no exista el nombre del menu
		 public function Buscaropcionmenu(){
                     	
			$opcion = trim($_POST["cmb_opcion"]);
             
                $sentencia ="select * from \"SYSTEM\".obtener_opcionmenu where id_opcion='".$opcion."'  ";
                $datos = $this->ejecutarConsulta($sentencia);
                $datos = $datos->fetchAll();
           

        return $datos;
        }

		 //Funcion para validar que no exista el nombre del menu
		 public function Buscarrolmenu($menu,$rol){
                     	
			 
             
                $sentencia ="select count(id_rol) from \"SYSTEM\".obtener_rolmenu where id_menu='".$menu."' and id_rol='".$rol."'  ";
                $datos = $this->ejecutarConsulta($sentencia);
                $datos = $datos->fetchAll();
           

        return $datos;
        }
 

		//Funcion para cambiar de estado al rol 
		public function cambiarestado($estado){ 

			$idcat = $_POST["rolopcion_id"];
				$sentencia ="select \"SYSTEM\".ELIMINAR_ROLOPCION('".$idcat."','".$estado."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute(); 

		 return $sql;
		}

    }