<?php

	namespace app\controllers;
	use app\models\mainModel;
	//delcaracion de funciones para el controlador
	class opcionrolController extends mainModel{
		//funcion para obtener los rols de la base de datos
        public function listar($idrol){
            if($idrol==0){
                $consulta_datos="select * from \"SACNSYS\".OBTENER_ROLOPCION where nombrevista not in('opcionrol');"; 
            }else{
                $consulta_datos="select * from \"SACNSYS\".OBTENER_ROLOPCION where id_rol='".$idrol."' and nombrevista not in('opcionrol');"; 
            }

			

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}
		//Funcion para guardar los datos del rol
		public function guardar(){
			 
			
			$idrol = trim($_POST["cmb_rol"]);			
			$opcion = trim($_POST["cmb_opcion"]);

			 
				$sentencia ="select \"SACNSYS\".INSERTAR_ROLOPCION('".$opcion."','".$idrol."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute();
			 

		 return $sql;
		}

		public function guardarrolmenu($menu,$rol){
		 

			 
				$sentencia ="select \"SACNSYS\".INSERTAR_ROLMENU('".$menu."','".$rol."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute();
			 

		 return $sql;
		}

        //Funcion para validar que no exista el nombre del menu
        public function Buscaropcion(){
                    
            $idrol = trim($_POST["cmb_rol"]);			
			$opcion = trim($_POST["cmb_opcion"]);
             
                $sentencia ="select * from \"SACNSYS\".OBTENER_rolopcion where id_rol='".$idrol."' and id_opcion='".$opcion."'  ";
                $datos = $this->ejecutarConsulta($sentencia);
                $datos = $datos->fetchAll();
           

        return $datos;
        }

		 //Funcion para validar que no exista el nombre del menu
		 public function Buscaropcionmenu(){
                     	
			$opcion = trim($_POST["cmb_opcion"]);
             
                $sentencia ="select * from \"SACNSYS\".obtener_opcionmenu where id_opcion='".$opcion."'  ";
                $datos = $this->ejecutarConsulta($sentencia);
                $datos = $datos->fetchAll();
           

        return $datos;
        }

		 //Funcion para validar que no exista el nombre del menu
		 public function Buscarrolmenu($menu,$rol){
                     	
			 
             
                $sentencia ="select count(id_rol) from \"SACNSYS\".obtener_rolmenu where id_menu='".$menu."' and id_rol='".$rol."'  ";
                $datos = $this->ejecutarConsulta($sentencia);
                $datos = $datos->fetchAll();
           

        return $datos;
        }
 

		//Funcion para cambiar de estado al rol 
		public function eliminar($estado){ 

			$idcat = $_POST["rolopcion_id"];
				$sentencia ="select \"SACNSYS\".ELIMINAR_ROLOPCION('".$idcat."','".$estado."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute(); 

		 return $sql;
		}

    }