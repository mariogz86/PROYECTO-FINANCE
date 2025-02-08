<?php

	namespace app\controllers;
	use app\models\mainModel;

	if(isset($_GET['correo'])){
		require_once '../phpmailer/src/PHPMailer.php';
		require_once '../phpmailer/src/SMTP.php';
		require_once '../phpmailer/src/Exception.php';
	}
	else{
		require_once 'phpmailer/src/PHPMailer.php';
		require_once 'phpmailer/src/SMTP.php';
		require_once 'phpmailer/src/Exception.php';
	}

 
	

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//declaracino de funciones 
	class loginController extends mainModel{

		/*----------  Controlador iniciar sesion  ----------*/
		public function iniciarSesionControlador(){
			
			$usuario=$this->limpiarCadena($_POST['login_usuario']);
		    $clave=$this->limpiarCadena($_POST['login_clave']);
			$hoy = date("Y-m-d"); 
			$_SESSION['cambioclave']="0";

		 

		    # Verificando campos obligatorios #
		    if($usuario=="" || $clave==""){
				echo '<article class="message is-danger">
				  <div class="message-body">
				    <strong>Ocurrió un error </strong><br>
				    No has llenado todos los campos que son obligatorios
				  </div>
				</article>';
		    }else{
 
					    # Verificando usuario #
					    $check_usuario=$this->ejecutarConsulta("SELECT r.rol,r.id_rol ,u.* 
						FROM \"SYSTEM\".usuarios u  
						inner join \"SYSTEM\".roles r on r.id_rol=u.id_rol
						WHERE usuario='$usuario'");

					    if($check_usuario->rowCount()==1){

					    	$check_usuario=$check_usuario->fetch();
							$_SESSION['time'] = time();
							
							$_SESSION['id']=$check_usuario['id_usuario'];
							$_SESSION['correo']=$check_usuario['u_email'];
							$_SESSION['nombre']=$check_usuario['u_nombre_completo'];
							$_SESSION['apellido']=$check_usuario['u_apellido_completo'];
							$_SESSION['usuario']=$check_usuario['usuario']; 
							$_SESSION['fechavenc']=$check_usuario['fecha_vencimiento']; 
							$_SESSION['claveant']=$check_usuario['u_clave']; 
							$_SESSION['estado']=$check_usuario['u_estado'];  
							$_SESSION['foto']="default"; 
							 $_SESSION['rol']=$check_usuario['rol'];
							 $_SESSION['idrol']=$check_usuario['id_rol']; 


							 
							if (is_null($check_usuario['cantidad_intento'])){
								$cantidadintentos=0;
							 }else{
								$cantidadintentos=$check_usuario['cantidad_intento'];
							 }

							//se valida si ya tiene 3 intentos o el estado del usuario esta de baja
							 if ($cantidadintentos==3 || $_SESSION['estado']==0){
									if ( $_SESSION['estado']==0){
										echo '<article class="message is-danger">
										<div class="message-body">
										  <strong>Ocurrió un error </strong><br>
										  Cuenta de usuario con estado de baja, contacte al administrador del sistema.
										</div>
									  </article>';
									}else{
										echo '<article class="message is-danger">
										<div class="message-body">
										  <strong>Ocurrió un error </strong><br>
										  Cuenta de usuario bloqueada, contacte al administrador del sistema.
										</div>
									  </article>';	
									}

								
							}else
							
							{

							
							//validacion del usuario y clave correcta
					    	if($check_usuario['usuario']==$usuario && password_verify($clave,$check_usuario['u_clave'])){

								$_SESSION['vistas']=[];

								$vistas=$this->ejecutarConsulta("SELECT o.nombrevista FROM \"SYSTEM\".rol_opcion RO 
								inner join \"SYSTEM\".opcion o on o.id_opcion=ro.id_opcion 
								where ro.id_rol=".$check_usuario['id_rol'].";");
								if($vistas->rowCount()>0){
									$_SESSION['vistas']=$vistas->fetchAll();
								}

								//validacion para verificar si esta vencida la clave o el usuario solicito resteo de contraseña
								if($hoy>=$_SESSION['fechavenc'] || $check_usuario['reset_clave'] ==1 ){
									$_SESSION['cambioclave']="1";

										if($check_usuario['reset_clave'] ==  1){
											echo '<article class="message is-warning">
											<div class="message-body">
											<strong>Validación de cuenta</strong><br>
											Se solicito reseteo de contraseña, proceda a cambiar clave.
											</div>
										</article>';
										}else{
											echo '<article class="message is-warning">
											<div class="message-body">
											<strong>Validación de cuenta</strong><br>
											La fecha de vencimiento de la cuenta expiró, proceda a cambiar clave.
											</div>
										</article>';
										}
										
								}else{
									$this->actualizarintentos(0,$_SESSION['id']);
								 
									if(headers_sent()){
											 
										echo "<script> window.location.href='".APP_URL."index.php?views=dashboard'; </script>";
									 
									}else{ 
										header("Location: ".APP_URL."index.php?views=dashboard"); 
										
									}
								}
					            

					    	}else{
								

								if ($cantidadintentos==3){
									echo '<article class="message is-danger">
									<div class="message-body">
									  <strong>Ocurrió un error </strong><br>
									  Cuenta de usuario bloqueada - Cuenta FIXMAN, favor contacte al administrador.
									</div>
								  </article>';
								}else{
									$cantidadintentos=$cantidadintentos+1;
									$this->actualizarintentos($cantidadintentos,$_SESSION['id']);

									if ($cantidadintentos==3){
										echo '<article class="message is-danger">
										<div class="message-body">
										  <strong>Ocurrió un error </strong><br>
										  Cuenta de usuario bloqueada - Cuenta FIXMAN, favor contacte al administrador.
										</div>
									  </article>';
									}else{
									echo '<article class="message is-danger">
									<div class="message-body">
									  <strong>Ocurrió un error </strong><br>
									  clave incorrecta, usted tiene '. $cantidadintentos .' de 3 intentos
									</div>
								  </article>';
									}
								}    	

							}	
					    }

					    }else{
							
							echo '<article class="message is-danger">
							  <div class="message-body">
							    <strong>Ocurrió un error </strong><br>
							    Usuario no existe en la base de datos.
							  </div>
							</article>';
					    }
				    } 
		}
		//funcion para cambiar la clave
		public function cambiarclave(){
			$claveant=$this->limpiarCadena($_POST['clave_anterior']);
		    $clavenue=$this->limpiarCadena($_POST['clave_nueva']);


			if(password_verify($claveant,$_SESSION['claveant'])){
			
				if ($claveant==$clavenue){
					$_SESSION['cambioclave']="1";
					echo '<article class="message is-danger">
								<div class="message-body">
									<strong>Ocurrió un error </strong><br>
									Clave anterior no puede ser igual a la nueva clave.
								</div>
								</article>';
				}else{
				
					$hoy = date("Y-m-d"); 
				$date_now = date('Y-m-d');
				$date = strtotime('+90 day', strtotime($date_now));
				$date = date('Y-m-d', $date);
				$clave=password_hash($clavenue,PASSWORD_BCRYPT,["cost"=>10]);
			
				 
					
					$sentencia ="select \"SYSTEM\".cambiar_clave(0,'".$clave."','".$date."','".$_SESSION['id']."');";
					$sql=$this->actualizarDatos($sentencia);
					$sql->execute();
					echo '<article class="message is-success">
					<div class="message-body">
						<strong>Cambio de clave</strong><br>
						Se ha cambiado la clave exitosamente
					</div>
					</article>';
					$_SESSION['cambioclave']="2";
				 

				
				
			}
		}
			else{
				$_SESSION['cambioclave']="1";
					echo '<article class="message is-danger">
								<div class="message-body">
									<strong>Ocurrió un error </strong><br>
									Clave anterior no es correcta, favor validar.
								</div>
								</article>';
			}
			



		}
	

		//funcion para actualizar los intentos del usuario para entrar al sistema
		public function actualizarintentos($cantidad,$idusuario){

			
			$hoy = date("Y-m-d"); 

			if ($cantidad==3){				
				$sentencia ="select \"SYSTEM\".actualizar_intentos('".$cantidad."','1','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
			$sql->execute();

			$this->enviarcorreocuentabloqueada();
			}else{
				$sentencia ="select \"SYSTEM\".actualizar_intentos('".$cantidad."','0','".$_SESSION['id']."');";
				$sql=$this->actualizarDatos($sentencia);
			$sql->execute();
			}
		}




		/*----------  Controlador cerrar sesion  ----------*/
		public function cerrarSesionControlador(){

			session_destroy();

		    if(headers_sent()){
                echo "<script> window.location.href='".APP_URL."'; </script>";
            }else{
                header("Location: ".APP_URL."");
            }
		}
		//funcion para enviar correo cuando la cuenta esta bloqueada por varios intentos
		public function enviarcorreocuentabloqueada(){
			$phpmailer = new PHPMailer();

			$phpmailer->SMTPOptions = array(
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true,
				)
			  );	
			$phpmailer->isSMTP();
			$phpmailer->CharSet = APP_CharSet;
			$phpmailer->Host = APP_Host;
			$phpmailer->SMTPAuth = APP_SMTPAuth;
			$phpmailer->Port = APP_Port;
			$phpmailer->Username = APP_Username;
			$phpmailer->Password = APP_Password;
			$phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
			$phpmailer->SMTPDebug = APP_SMTPDebug;

			$phpmailer->setFrom(APP_Username);
			$phpmailer->addAddress($_SESSION['correo']);
			$phpmailer->isHTML(true); // Set email format to plain text

			$phpmailer->Subject = "Cuenta de usuario bloqueada - FIXMAN";
			$phpmailer->Body    = "Estimado(a) <b>" . $_SESSION['nombre'] . " " . $_SESSION['apellido'] . "</b>:<br><br>"
				. "Le informamos que su cuenta de usuario <b>".$_SESSION['usuario']."</b> del sistema <b>Automatizado de Cuentas Nacionales FIXMAN</b> ha sido bloqueada, "
				. "debido a que realizó la cantidad de 3 intentos de inicio de sesión fallidos.<br><br>"
				. "Para mayor información favor contactar al administrador del sistema para desbloquear su cuenta.";

				if(!$phpmailer->send()){ 
					echo  'Error en el envío de correo electrónico, Error: ' . $phpmailer->ErrorInfo .
								' Para mayor información pongase en contacto con la Gerencia de sistemas informáticos';
						
					} 
		}

		//funcino para resetear contraseña y enviar clave nueva al correo del usuario
		public function resetearcontraseña(){

			$email=$this->limpiarCadena($_GET['correo']);
			$clavetemp=$this->generatePassword(8);

			  # Verificando usuario #
			  $check_usuario=$this->ejecutarConsulta("SELECT r.rol,r.id_rol ,u.* 
			  FROM \"SYSTEM\".usuarios u  
			  inner join \"SYSTEM\".roles r on r.id_rol=u.id_rol
			  WHERE u_email='$email'");

			  if($check_usuario->rowCount()==1){

				$check_usuario=$check_usuario->fetch();
				if($check_usuario['u_estado']==0){
					return 4;
				}
					if($check_usuario['u_bloqueado']==1){
						return 3;
					}
					else{

						$this->actualizarclave($clavetemp,$check_usuario['id_usuario']);
								

						$phpmailer->SMTPOptions = array(
							'ssl' => array(
								'verify_peer' => false,
								'verify_peer_name' => false,
								'allow_self_signed' => true,
							)
						  );	
						$phpmailer->isSMTP();
						$phpmailer->CharSet = APP_CharSet;
						$phpmailer->Host = APP_Host;
						$phpmailer->SMTPAuth = APP_SMTPAuth;
						$phpmailer->Port = APP_Port;
						$phpmailer->Username = APP_Username;
						$phpmailer->Password = APP_Password;
						$phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
						$phpmailer->SMTPDebug = APP_SMTPDebug;

						$phpmailer->setFrom(APP_Username);
						$phpmailer->addAddress($email);
						$phpmailer->isHTML(true); // Set email format to plain text

						$phpmailer->Subject = "Resteo clave de usuario - FIXMAN";
						$phpmailer->Body    = "Estimado(a) <b>" . $check_usuario['u_nombre_completo'] . " " . $check_usuario['u_apellido_completo'] . "</b>:<br><br>"
							. "Le informamos que su clave  de usuario temporal es <b>".$clavetemp." </b>"
							. "favor de cambiar su clave al ingresar al sistema nuevamente.<br><br>"
							. "Para mayor información favor contactar al administrador del sistema para desbloquear su cuenta.";

						$phpmailer->send();

						

						return 1;
					}
				}
				else{
									
					return 2;
				}
		}
		//funcion para generar una clave temporal para el usuario
		function generatePassword($length)
		{
			$key = "";
			$pattern = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
			$max = strlen($pattern)-1;
			for($i = 0; $i < $length; $i++){
				$key .= substr($pattern, mt_rand(0,$max), 1);
			}
			return $key;
		}

		//funcion para actualizar la clave
		public function actualizarclave($clave,$idusuario){
			$hoy = date("Y-m-d");  
			$clavenueva=password_hash($clave,PASSWORD_BCRYPT,["cost"=>10]);				
			$sentencia ="select \"SYSTEM\".cambiar_clave(1,'".$clavenueva."','".$hoy."','".$idusuario."');";
			$sql=$this->actualizarDatos($sentencia);
			$sql->execute();
 
		}


	}