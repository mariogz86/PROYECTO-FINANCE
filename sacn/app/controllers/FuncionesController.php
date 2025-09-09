<?php

	namespace app\controllers;
	use app\models\mainModel;
	//declaracion de funciones generales para uso de logica de programacion
	class FuncionesController extends mainModel{
		//funcion para contrar registros segun la consulta proporcionada para la base de datos
		public function ContarRegistros($consulta){			
			$total = $this->ejecutarConsulta($consulta);
			$total = (int) $total->fetchColumn(); 
			return $total;
		}

		//funcion para contrar registros segun la consulta proporcionada para la base de datos
		public function obteneridgenerado($consulta){			
			$total = $this->ejecutarConsulta($consulta);
			$total = (int) $total->fetchColumn(); 
			return $total;
		}
		//funcion para ejecutar cualquier consulta enviada
		public function Ejecutar($consulta){			
			$total = $this->ejecutarConsulta($consulta); 
			return $total;
		}
		//funcion que recibe una consulta y retorna los resultados en forma de arreglo
		public function ejecutarconsultaarreglo($consulta){
			 
			$datos = $this->ejecutarConsulta($consulta);
			$datos = $datos->fetchAll();

			return $datos;
		}

		//funcion para la carga del menu principal
		public function configurarMenu(){
		 
			$consulta_datos="select * from \"SACNSYS\".obtenermenu(".$_SESSION['idrol'].");";
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

		 
			$consultasubmenu2="select * from \"SACNSYS\".obteneropciones(".$_SESSION['idrol'].");";
			$datossubmenu2 = $this->ejecutarConsulta($consultasubmenu2);
			if($datossubmenu2->rowCount()>0){

				$datossubmenu2 = $datossubmenu2->fetchAll(); 
			}

			$menu=""; 

			  foreach($datos as $rows){ 
				if ($rows['id_menupadre']=='0'){
					$menu.='<li class="full-width divider-menuprincipal-h"></li>';
						$menu.='<li class="full-width">
						<a id='.$rows['id_menupadre'].' href="#" class="full-width btn-subMenu">
							<div class="navLateral-body-cl">
							'.$this->CambiarCaracter($rows['icono'],"\\","").'
							</div>
							<div class="navLateral-body-cr">
							'.$rows['nombre'].'
							</div>
							<span class="fas fa-chevron-down"></span>
						</a>';
						$menu.=$this->crearopcion($rows['id_menu'],$datossubmenu2);     
						$menu.='</li>'; 
						
					}  
				}
			  

			
			return $menu;
		}

		//funcion para cargar las opciones hijas de un menu
		public function  crearopcion($idmenu,$datossubmenu2){
			$menu=""; 
 
				$menu.='<ul class="full-width menu-principal sub-menu-options">';
				
				foreach($datossubmenu2 as $rowssubmenu){
					if($rowssubmenu['id_menu']==$idmenu){ 
					$menu.='<li class="full-width divider-menu-h"></li>'; 
					 
						$menu.='<li class="full-width">						
					<a id='.$rowssubmenu['nombrevista'].' href="'.APP_URL."index.php?views=".$rowssubmenu['nombrevista'].'" class="full-width">
							<div class="navLateral-body-cl">
							'.$this->CambiarCaracter($rowssubmenu['icono'],"\\","").'
							</div>
							<div class="navLateral-body-cr">
							'.$rowssubmenu['nombre'].'
							</div>
							</a>
						</li>';
					} 
					 
				} 
				$menu.='</ul>';
			 
		return $menu;
		}
 

	}