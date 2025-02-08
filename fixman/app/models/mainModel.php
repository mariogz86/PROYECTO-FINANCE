<?php

	namespace app\models;
	use \PDO;

	if(file_exists(__DIR__."/../../config/server.php")){
		require_once __DIR__."/../../config/server.php";
	}

	class mainModel{

		private $server=DB_SERVER;
		private $db=DB_NAME;
		private $user=DB_USER;
		private $pass=DB_PASS;



		/*----------  Funcion conectar a BD  ----------*/
		protected function conectar(){
			global $conexion;
			// $conexion = new PDO("pgsql:host=".$this->server.";dbname=".$this->db,$this->user,$this->pass, array(
			// 	PDO::ATTR_PERSISTENT => true));
			  
			try
            {
				$conexion =new PDO("pgsql:host= $this->server;dbname=$this->db", $this->user, $this->pass);  
				
            }
            catch (PDOException $e)
            {
            exit("Ocurrio un Error: $e");
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
            catch (PDOException $e)
            {
            exit("Ocurrio un Error: $e");
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

			 
			$sql=$this->conectar()->prepare($query);		
 
			// $this->guardarevento(json_encode($sql->queryString),"Actualizar",$tabla,json_encode($datos).json_encode($condicion));

			return $sql;
		}

  

		/*----------  Funcion generar codigos aleatorios  ----------*/
		protected function generarCodigoAleatorio($longitud,$correlativo){
			$codigo="";
			$caracter="Letra";
			for($i=1; $i<=$longitud; $i++){
				if($caracter=="Letra"){
					$letra_aleatoria=chr(rand(ord("a"),ord("z")));
					$letra_aleatoria=strtoupper($letra_aleatoria);
					$codigo.=$letra_aleatoria;
					$caracter="Numero";
				}else{
					$numero_aleatorio=rand(0,9);
					$codigo.=$numero_aleatorio;
					$caracter="Letra";
				}
			}
			return $codigo."-".$correlativo;
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

	}