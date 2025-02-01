<?php
// /* Permitimos a cualquier origen acceder a este API de manera remota */
// header('Access-Control-Allow-Origin:*');
// if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
//   /* No debe ejecutarse el resto del script mediante la consulta OPTIONS previa */
//   die();
// }
	
	require_once "../config/app.php";
	require_once "../app/views/inc/session_start.php";
	require_once "../autoload.php";
	
	//incializando controlador
	use app\controllers\loginController;
	$insRol = new loginController();

 
	
	

	//Metodo GET para reseteo de contraseña
	if(isset($_GET['correo']))
		{
			//llamada al metodo en el controlador
			$result =$insRol->resetearcontraseña();
			//resultado que se envia al metodo invocado
			if($result==1 )
			{ 
				//1=se resetea la contraseña
				$res = array (
					'status' => 200,
					'message' =>  "<article class=\"message is-success\">
							  <div class=\"message-body\">
							    <strong>Envió de correo</strong><br>
							    Se ha enviado clave al correo, favor de validar.
							  </div>
							</article>",
					'data' => $result
						);
			
			}
			else
			{
				if($result==2 ){
					//2= el correo no existe en el sistema
				$res = array (
					'status' => 404,
					'message' =>  "<article class=\"message is-danger\">
							  <div class=\"message-body\">
							    <strong>Envió de correo</strong><br>
							    El correo indicado no pertenece a un usuario del sistema. 
							  </div>
							</article>",
					);
				}else{
					if($result==4 ){
						//4=el usuario esta de baja en el sistema
						$res = array (
							'status' => 404,
							'message' =>  "<article class=\"message is-danger\">
										<div class=\"message-body\">
										  <strong>Ocurrió un error </strong><br>
										  Cuenta de usuario con estado de baja, contacte al administrador del sistema.
										</div>
									  </article>",
							); 
					}
					else{
						//3= usuario bloqueado en el sistema
					$res = array (
						'status' => 404,
						'message' =>  "<article class=\"message is-danger\">
								  <div class=\"message-body\">
									<strong>Envió de correo</strong><br>
									 Cuenta de usuario bloqueada - no se puede resetear contraseña.
								  </div>
								</article>",
						);

					}
				}
			
			}    
			echo json_encode($res); 
		}