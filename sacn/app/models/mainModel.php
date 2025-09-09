<?php

	namespace app\models;
	use \PDO;

	if(file_exists(__DIR__."/../../config/server.php")){
		require_once __DIR__."/../../config/server.php";
	}
	ini_set('log_errors', 1);

	class mainModel{

		private $server=DB_SERVER;
		private $db=DB_NAME;
		private $user=DB_USER;
		private $pass=DB_PASS;
		private $port=DB_PUERTO;



		/*----------  Funcion conectar a BD  ----------*/
		protected function conectar(){
			global $conexion;
			// $conexion = new PDO("pgsql:host=".$this->server.";dbname=".$this->db,$this->user,$this->pass, array(
			// 	PDO::ATTR_PERSISTENT => true));
			  
			try
            {
				$conexion =new PDO("pgsql:host= $this->server;port=$this->port;dbname=$this->db", $this->user, $this->pass,[
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // Asegúrate de que PDO lance excepciones
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				]);  
				
            }
			catch (PDOException  $e) {
				error_log("Error: " . $e->getMessage(), 3, '../Error/log.txt');
			}
			// $conexion->exec("SET CHARACTER SET utf8");
			return $conexion;
		}


		/*----------  Funcion ejecutar consultas  ----------*/
		protected function ejecutarConsulta($consulta){
			
			try
            {
				$sql=$this->conectar()->prepare($consulta);
				$sql->execute();
				return $sql;
            }
			catch (PDOException  $e) {
				error_log("Error: " . $e->getMessage(), 3, '../Error/log.txt');
			}

		}
 
		/*----------  Funcion limpiar cadenas  ----------*/
		public function limpiarCadena($cadena){

			$palabras=["<script>","</script>","<script src","<script type=","SELECT * FROM","SELECT "," SELECT ","DELETE FROM","INSERT INTO","DROP TABLE","DROP DATABASE","TRUNCATE TABLE","SHOW TABLES","SHOW DATABASES","<?php","?>","--","^","<",">","==",";","::","\""];

			$cadena=trim($cadena);
			$cadena=stripslashes($cadena);

			foreach($palabras as $palabra){
				$cadena=str_ireplace($palabra, "", $cadena);
			}

			$cadena=trim($cadena);
			$cadena=stripslashes($cadena);

			return $cadena;
		}

		public function CambiarCaracter($cadena,$simbolo,$porsimbolo){

			$palabras=[$simbolo];

			$cadena=trim($cadena);
			$cadena=stripslashes($cadena);

			foreach($palabras as $palabra){
				$cadena=str_ireplace($palabra, $porsimbolo, $cadena);
			}

			$cadena=trim($cadena);
			$cadena=stripslashes($cadena);

			return $cadena;
		}


		/*---------- Funcion verificar datos (expresion regular) ----------*/
		protected function verificarDatos($filtro,$cadena){
			if(preg_match("/^".$filtro."$/", $cadena)){
				return false;
            }else{
                return true;
            }
		}


		
 

		/*----------  Funcion para ejecutar una consulta UPDATE preparada  ----------*/
		protected function actualizarDatos($query){

			try
            {
				$sql=$this->conectar()->prepare($query);		
            }
			catch (PDOException  $e) {
				error_log("Error: " . $e->getMessage(), 3, '../Error/log.txt');
			}
			
 
			// $this->guardarevento(json_encode($sql->queryString),"Actualizar",$tabla,json_encode($datos).json_encode($condicion));

			return $sql;
		}

  
 

		/*----------  Limitar cadenas de texto  ----------*/
		public function limitarCadena($cadena,$limite,$sufijo){
			if(strlen($cadena)>$limite){
				return substr($cadena,0,$limite).$sufijo;
			}else{
				return $cadena;
			}
		}

		public function ContarRegistros($consulta){			
			$total = $this->ejecutarConsulta($consulta);
			$total = (int) $total->fetchColumn(); 
			return $total;
		}

		   /*----------  Funcion generar select ----------*/
		   public function generarSelect($datos,$campo_db){
			$check_select='';
			$text_select='';
			$count_select=1;
			$select='';
			foreach($datos as $row){

				if($campo_db==$row){
					$check_select='selected=""';
					$text_select=' (Actual)';
				}

				$select.='<option value="'.$row.'" '.$check_select.'>'.$row.$text_select.'</option>';

				$check_select='';
				$text_select='';
				$count_select++;
			}
			return $select;
		}

	}