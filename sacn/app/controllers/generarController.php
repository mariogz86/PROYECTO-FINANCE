<?php

	namespace app\controllers;
	use app\models\mainModel;

	class generarController extends mainModel{

        //funcion para obtener las boletas cargadas para un formulario
        public function obtenerboletas_por_idformulario($id){
                    
            $consulta_datos="select distinct boleta from \"SACNSYS\".valorvariable where id_formulario='".$id."'   order by boleta asc"; 

            $datos = $this->ejecutarConsulta($consulta_datos);
            $datos = $datos->fetchAll();

            return $datos;
        }


         //funcion para obtener las boletas cargadas para un formulario
         public function OBTENER_BOLETASFORMULARIO($formulario,$anio){
                    
            $consulta_datos="select * from \"SACNSYS\".OBTENER_BOLETASFORMULARIO('".$formulario."','".$anio."')"; 

            $datos = $this->ejecutarConsulta($consulta_datos);
            $datos = $datos->fetchAll();

            return $datos;
        }

         //funcion para obtener las boletas cargadas para un formulario
         public function obtenernombreempresa($boleta,$formulario){
                    
             $consulta_datos="select * from \"SACNSYS\".OBTENER_NOMBREEMPRESAIND('".$boleta."','".$formulario."')"; 
            $datos = $this->ejecutarConsulta($consulta_datos);
            $datos = $datos->fetchAll();

            return $datos;
        }


          public function obtenerdatos_formulario($id){
                    
            $consulta_datos="select * from \"SACNSYS\".obtener_formulario where id_formulario='".$id."'"; 

            $datos = $this->ejecutarConsulta($consulta_datos);
            $datos = $datos->fetchAll();

            return $datos;
        }


        //funcion para obtener las valores de las variables de una boleta para un formulario
        public function obtenervalorboleta_formulario($boleta,$formulario,$anio){
                    
            $consulta_datos="select * from \"SACNSYS\".OBTENER_VALORESBOLETA('".$boleta."','".$formulario."','".$anio."') where TRIM(valor)  not in ('')"; 

            $datos = $this->ejecutarConsulta($consulta_datos);
            $datos = $datos->fetchAll();

            return $datos;
        }

        //funcion para obtener las valores de las variables de una boleta para un formulario
        public function OBTENERPOSICIONDETALLE($archivo,$variable,$formulario){
                    
            $consulta_datos="select * from \"SACNSYS\".OBTENERPOSICIONDETALLE('".$archivo."','".$variable."','".$formulario."')"; 

            $datos = $this->ejecutarConsulta($consulta_datos);
            $datos = $datos->fetchAll();

            return $datos;
        }

        //funcion para obtener las valores de las variables de una boleta para un formulario
        public function OBTENERAAGRUPACION($boleta,$archivo,$formulario){ 

                switch ($archivo) {
                    case "AIF_CO_Capitulo7_2.sav":
                    case "AIF_CO_Capitulo7_1_1.sav":
                    case "AIF_CO_Capitulo7_1.sav":
                        $consulta_datos="select * from \"SACNSYS\".OBTENERAAGRUPACION('".$boleta."','".$archivo."','".$formulario."') AS t(MER0001 text, MER0002 text, MER0003 text,MER0004 numeric,MER0005 numeric,MER0006 numeric,MER0016 numeric,MER0017 numeric,MER0021 numeric,MER0010 numeric,MER0011 numeric,MER0018 numeric,MER0013 numeric,MER0014 numeric,MER0015 numeric,MVT0001 numeric,MVT0002 numeric,MVT0003 numeric,MVT0004 numeric,MVT0005 numeric,MVT0006 numeric,MCT0001 numeric,MCT0002 numeric,MCT0003 numeric,MCT0004 numeric,MCT0005 numeric,MCT0006 numeric);"; 
                        break;

                    case "AIF_CO_Capitulo6.sav":
                    $consulta_datos="select * from \"SACNSYS\".OBTENERAAGRUPACION('".$boleta."','".$archivo."','".$formulario."') AS t(MPU0001 text,MPU0002 text,MPU0003 text,  MPU0005 numeric,    MPU0007 numeric,    MPU0009  numeric,   MPU0025 numeric,    MPU0027 numeric,    MPU0039 numeric,    MPU0017 numeric,    MPU0029 numeric,    MPU0033 numeric,    MPU0019 numeric,    MPU0021 numeric, MVTI0001 numeric,  MVTI0002 numeric,   MVTI0003   numeric, MVTI0004   numeric, MVTI0005 numeric,   MVTI0006 numeric,   MCIT0001   numeric, MCIT0002 numeric,   MCIT0003 numeric,   MCIT0004 numeric,   MCIT0005 numeric,   MCIT0006 numeric);"; 
                        break;

                    case "AIF_CO_Capitulo8.sav":
                    $consulta_datos="select * from \"SACNSYS\".OBTENERAAGRUPACION('".$boleta."','".$archivo."','".$formulario."') AS t(PRO0001 text,PRO0002 text,PRO0003 text, PRO0005 numeric, PRO0006 numeric,    PRO0008 numeric,    PRO0010 numeric,    PRO0023 numeric,    PRO0028 numeric, PRO0020 numeric, MVP0001 numeric,  MVP0002    numeric,  MVP0003   numeric,  MVP0004 numeric,   MVP0005 numeric,    MVP0006 numeric);"; 
                        break;

                    case "AIF_CO_Anexo2_D.sav":
                    case "E_AIF_CO_Anexo2_D.sav":
                    case "AIF_IND_Anexo2_D.sav":
                    case "E_AIF_IND_Anexo2_D.sav":
                    case "AIF_SA_Anexo2_D.sav":
                    case "E_AIF_SA_Anexo2_D.sav":
                    case "AIF_EN_Anexo2_D.sav":
                    case "E_AIF_EN_Anexo2_D.sav":
                    case "AIF_SE_Anexo2D.sav":
                    case "E_AIF_SE_Anexo2D.sav":
                    case "AIF_R_Anexo2D.sav":
                    case "E_AIF_R_Anexo2D.sav":
                    case "AIF_H_Anexo2D.sav":
                    case "E_AIF_H_Anexo2D.sav":
                    case "AIF_IF_Anexo2D.sav":
                    case "E_AIF_IF_Anexo2D.sav":
                    case "AIF_VE_Anexo2D.sav":
                    case "E_AIF_VE_Anexo2D.sav":
                    case "AIF_SC_Anexo2D.sav":
                    case "E_AIF_SC_Anexo2D.sav":
                    case "AIF_AG_ANEXO2D.sav":
                        $consulta_datos="select * from \"SACNSYS\".OBTENERAAGRUPACION('".$boleta."','".$archivo."','".$formulario."') AS t(OSP0001  text, OSP0002 numeric,  OSP0003 numeric,    OSP0004 numeric);"; 
                        break;

                    case "AIF_CO_Anexo3_D.sav":
                    case "E_AIF_CO_Anexo3_D.sav":
                    case "AIF_IND_Anexo3_D.sav":    
                    case "E_AIF_IND_Anexo3_D.sav":  
                    case "AIF_SA_Anexo3_D.sav":    
                    case "E_AIF_SA_Anexo3_D.sav":      
                    case "AIF_EN_Anexo3_D.sav":    
                    case "E_AIF_EN_Anexo3_D.sav": 
                    case "AIF_SE_Anexo3D.sav":
                    case "E_AIF_SE_Anexo3D.sav":   
                    case "AIF_R_Anexo3D.sav":
                    case "E_AIF_R_Anexo3D.sav":   
                    case "AIF_H_Anexo3D.sav":
                    case "E_AIF_H_Anexo3D.sav": 
                    case "AIF_IF_Anexo3D.sav":
                    case "E_AIF_IF_Anexo3D.sav": 
                    case "AIF_VE_Anexo3D.sav":
                    case "E_AIF_VE_Anexo3D.sav":
                     case "AIF_SC_Anexo3D.sav":
                    case "E_AIF_SC_Anexo3D.sav":
                    case "AIF_AG_ANEXO3D.sav":
                        $consulta_datos="select * from \"SACNSYS\".OBTENERAAGRUPACION('".$boleta."','".$archivo."','".$formulario."') AS t(OGT0001 text, OGT0002 numeric,  OGT0003 numeric,    OGT0004 numeric);"; 
                        break;
                    case "AIF_IND_Capitulo6.sav":
                    $consulta_datos="select * from \"SACNSYS\".OBTENERAAGRUPACION('".$boleta."','".$archivo."','".$formulario."') AS t(PRO0001 text,PRO0002 text,PRO0003 text, PRO0004 numeric,PRO0005 numeric, PRO0006 numeric, PRO0007 numeric,    PRO0008 numeric,PrecioN numeric, PRO0009 numeric,    PRO0010 numeric, PrecioE numeric,  PRO0022 numeric, PRO0023 numeric,PRO0027 numeric,    PRO0028 numeric,PRO0019 numeric, PRO0020 numeric, MVP0001 numeric,  MVP0002    numeric,  MVP0003   numeric,  MVP0004 numeric,   MVP0005 numeric,    MVP0006 numeric,CPNIC_P text);"; 
                        break;

                    case "AIF_IND_Capitulo9.sav":
                    $consulta_datos="select * from \"SACNSYS\".OBTENERAAGRUPACION('".$boleta."','".$archivo."','".$formulario."') AS t(MER0001 text,MER0003 text,MER0004 numeric,MER0005 numeric,MER0006 numeric,MER0016 numeric,MER0017 numeric,MER0021 numeric,MER0010 numeric,MER0011 numeric,MER0018 numeric,MER0013 numeric,MVT0001 numeric,MVT0002 numeric,MVT0003 numeric,MVT0004 numeric,MVT0005 numeric,MVT0006 numeric,MCT0001 numeric,MCT0002 numeric,MCT0003 numeric,MCT0004 numeric,MCT0005 numeric,MCT0006 numeric);"; 
                            break;
                            
                    case "AIF_IND_Capitulo7.sav":
                    $consulta_datos="select * from \"SACNSYS\".OBTENERAAGRUPACION('".$boleta."','".$archivo."','".$formulario."') AS t(SIN0001 text,SIN0003 text,SIN0006 numeric,SIN0007 numeric);"; 
                            break; 
                    case "AIF_IND_Capitulo10.sav":
                    $consulta_datos="select * from \"SACNSYS\".OBTENERAAGRUPACION('".$boleta."','".$archivo."','".$formulario."') AS t(MPU0001 text,MPU0002 text,MPU0003 text,MPU0004 numeric,MPU0005 numeric,MPU0006 numeric,MPU0007 numeric,PrecioIN numeric,MPU0008 numeric,MPU0009 numeric,PrecioIE numeric,MPU0024 numeric,MPU0025 numeric,MPU0026 numeric,MPU0027 numeric,MPU0038 numeric,MPU0039 numeric,MPU0016 numeric,MPU0017 numeric,MPU0028 numeric,MPU0029 numeric,MPU0032 numeric ,MPU0033 numeric ,MPU0018 numeric ,MPU0019 numeric ,MPU0020 numeric ,MPU0021 numeric,CPNIC_MP text);"; 
                            break; 
                    case "AIF_IND_Capitulo11.sav":
                    $consulta_datos="select * from \"SACNSYS\".OBTENERAAGRUPACION('".$boleta."','".$archivo."','".$formulario."') AS t(MEI0001 text,MEI0002 text,MEI0003 text,MEI0004 numeric,MEI0005 numeric,MEI0006 numeric,MEI0007 numeric,PrecioEN numeric,MEI0008 numeric,MEI0009 numeric,PrecioEE numeric,MEI0024 numeric,MEI0025 numeric,MEI0026 numeric,MEI0027 numeric,MEI0038 numeric,MEI0039 numeric,MEI0016 numeric,MEI0017 numeric,MEI0028 numeric,MEI0029 numeric,MEI0032 numeric,MEI0033 numeric,MEI0018 numeric,MEI0019 numeric,MEI0020 numeric,MEI0021 numeric, CPNIC_ME text  );"; 
                            break; 
                    case "AIF_SA_Capitulo7.sav":
                    $consulta_datos="select * from \"SACNSYS\".OBTENERAAGRUPACION('".$boleta."','".$archivo."','".$formulario."') AS t(MPU0001	 text,MPU0003 text,MPU0005 numeric,MPU0007 numeric,	MPU0009 numeric,	MPU0025 numeric,MPU0027 numeric,MPU0039 numeric,	MPU0017 numeric,MPU0029 numeric,MPU0033 numeric,MPU0019 numeric,MPU0021 numeric);"; 
                            break; 
                    case "AIF_SA_Capitulo8.sav":
                    $consulta_datos="select * from \"SACNSYS\".OBTENERAAGRUPACION('".$boleta."','".$archivo."','".$formulario."') AS t(MER0001 text, MER0002 text,MER0004 numeric,MER0005 numeric,MER0006 numeric,MER0016 numeric,MER0017 numeric,MER0021 numeric,MER0010 numeric,MER0011 numeric,MER0018 numeric,MER0013 numeric,MER0014 numeric,MER0015 numeric);"; 
                            break; 
                    case "AIF_EN_Capitulo7.sav":
                    case "AIF_R_Capitulo7.sav":
                    $consulta_datos="select * from \"SACNSYS\".OBTENERAAGRUPACION('".$boleta."','".$archivo."','".$formulario."') AS t(MPU0001 text,MPU0003 text,  MPU0005 numeric,    MPU0007 numeric,    MPU0008  numeric,   MPU0025 numeric,    MPU0027 numeric,    MPU0039 numeric,    MPU0017 numeric,    MPU0029 numeric,    MPU0033 numeric,    MPU0019 numeric,    MPU0021 numeric, MVIT0001 numeric,  MVIT0002 numeric,   MVIT0003   numeric, MVIT0004   numeric, MVIT0005 numeric,   MVIT0006 numeric,   MCIT0001   numeric, MCIT0002 numeric,   MCIT0003 numeric,   MCIT0004 numeric,   MCIT0005 numeric,   MCIT0006 numeric);"; 
                            break; 
                    case "AIF_EN_Capitulo8.sav":
                    case "AIF_SE_Capitulo8.sav":                        
                    case "AIF_R_Capitulo8.sav":
                    case "AIF_H_Capitulo8.sav":
                    case "AIF_IF_Capitulo8.sav":
                    case "AIF_VE_Capitulo8.sav":
                    case "AIF_SC_Capitulo8.sav":
                    $consulta_datos="select * from \"SACNSYS\".OBTENERAAGRUPACION('".$boleta."','".$archivo."','".$formulario."') AS t(MER0001 text, MER0002 text,MER0004 numeric,MER0005 numeric,MER0006 numeric,MER0016 numeric,MER0017 numeric,MER0021 numeric,MER0010 numeric,MER0011 numeric,MER0018 numeric,MER0013 numeric,MER0014 numeric,MER0015 numeric,MVT0001 numeric,MVT0002 numeric,MVT0003 numeric,MVT0004 numeric,MVT0005 numeric,MVT0006 numeric,MCT0001 numeric,MCT0002 numeric,MCT0003 numeric,MCT0004 numeric,MCT0005 numeric,MCT0006 numeric);"; 
                            break; 
                    case "AIF_SE_Capitulo7.sav":
                    case "AIF_H_Capitulo7.sav":
                    case "AIF_IF_Capitulo7.sav":
                    case "AIF_VE_Capitulo7.sav":
                    case "AIF_SC_Capitulo7.sav":
                    $consulta_datos="select * from \"SACNSYS\".OBTENERAAGRUPACION('".$boleta."','".$archivo."','".$formulario."') AS t(MPU0001 text,MPU0003 text,  MPU0005 numeric,    MPU0007 numeric,    MPU0009  numeric,   MPU0025 numeric,    MPU0027 numeric,    MPU0039 numeric,    MPU0017 numeric,    MPU0029 numeric,    MPU0033 numeric,    MPU0019 numeric,    MPU0021 numeric, MVIT0001 numeric,  MVIT0002 numeric,   MVIT0003   numeric, MVIT0004   numeric, MVIT0005 numeric,   MVIT0006 numeric,   MCIT0001   numeric, MCIT0002 numeric,   MCIT0003 numeric,   MCIT0004 numeric,   MCIT0005 numeric,   MCIT0006 numeric);"; 
                            break; 

                    default:
                        // Opcional: manejar archivos no contemplados
                        $consulta_datos = null;
                        break;
                }
            

            $datos = $this->ejecutarConsulta($consulta_datos);
            $datos = $datos->fetchAll();

            return $datos;
        }

        function limpiarletras($str) {
            return $str = intval(preg_replace('/[^0-9]+/', '', $str), 10); 
      
        }

        function limpiarnumeros($str) {
            return $str = preg_replace('/[0-9]+/', '', $str);
         
        }
        


        
}