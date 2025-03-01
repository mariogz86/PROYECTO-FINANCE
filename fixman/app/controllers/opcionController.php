<?php

	namespace app\controllers;
	use app\models\mainModel;
	//delcaracion de funciones para el controlador
	class opcionController extends mainModel{
		//funcion para obtener los rols de la base de datos
        public function listar(){
			$consulta_datos="select * from \"SYSTEM\".OBTENER_OPCIONMENU;"; 

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			return $datos;
		}
		//Funcion para guardar los datos del rol
		public function guardar(){
			 
			
			$nombre = $this->limpiarCadena(trim($_POST["nombre"]));
            $descripcion = $this->limpiarCadena(trim($_POST["descripcion"]));	
            $nombrevista= $this->limpiarCadena(trim($_POST["nombrevista"]));		
			$menu = trim($_POST["cmb_menu"]);					
			$orden = trim($_POST["orden"]);
			$icono = trim($_POST["icono"]);

			if ($_POST["idopcion"]=="0"){
				$sentencia ="select \"SYSTEM\".INSERTAR_OPCION('".$nombre."','".$nombrevista."','".$descripcion."','".$menu."','".$icono."','".$orden."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute();
			}else{				
				$sentencia ="select \"SYSTEM\".ACTUALIZAR_OPCION('".$_POST["idopcion"]."','".$nombre."','".$nombrevista."','".$descripcion."','".$menu."','".$icono."','".$orden."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute();	

			} 

		 return $sql;
		}

        //Funcion para validar que no exista el nombre del menu
        public function Buscaropcion(){
                    
            $nombre = $this->limpiarCadena(trim($_POST["nombre"]));			

            if ($_POST["idopcion"]=="0"){
                $sentencia ="select * from \"SYSTEM\".OBTENER_OPCIONMENU where UPPER(nombre)=UPPER('".$nombre."');  ";
                $datos = $this->ejecutarConsulta($sentencia);
                $datos = $datos->fetchAll();
            }else{				
                $sentencia ="select * from \"SYSTEM\".OBTENER_OPCIONMENU where UPPER(nombre)=UPPER('".$nombre."') and id_opcion not in ('".$_POST["idopcion"]."');  ";
                $datos = $this->ejecutarConsulta($sentencia);
			$datos = $datos->fetchAll();

            } 

        return $datos;
        }
 

		//Funcion para cambiar de estado al rol 
		public function cambiarestado($estado){ 

			$idcat = $_POST["id_opcion"];
				$sentencia ="select \"SYSTEM\".CAMBIARESTADO_OPCION('".$idcat."','".$estado."','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
				$sql->execute(); 

		 return $sql;
		}

    }