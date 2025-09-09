<?php
	
	namespace app\models;

	class viewsModel{

		/*---------- Modelo obtener vista ----------*/
		protected function obtenerVistasModelo($vista){

			$listaBlanca=VISTAS;
			
			$permiso=0;
			

			

			if(in_array($vista, $listaBlanca)){				 
				if(is_file("./app/views/content/".$vista."-view.php")){ 
					
					

					if($_SESSION['vistas']==null){
						$listaVistas=[]; 
					}else
					{
						$listaVistas=$_SESSION['vistas'];
						foreach( $listaVistas as $rows  ){
							if($vista=="logOut" || $vista=="dashboard" || $vista=="index1" ){
								$permiso=1;
							}else{
								if($vista==$rows['nombrevista']){ 
									$permiso="1";
	
								}
							}
							
						} 
					}
					

					

					if($permiso=="1"){
						$contenido="./app/views/content/".$vista."-view.php";
					}else{
						$contenido="sinpermiso";
					}
					
				}else{
					
				}
			}elseif($vista=="login" || $vista=="index"){
				$contenido="login";
			}else{
				$contenido="404";
			}

			
			return $contenido;
		}

	}