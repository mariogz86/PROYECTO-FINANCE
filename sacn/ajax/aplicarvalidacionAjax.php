<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
require_once('../tcpdf/examples/tcpdf_include.php');
require_once "../autoload.php";



//incializar controladores
use app\controllers\aplicarvalidacionController;

$inscarga = new aplicarvalidacionController();

use app\controllers\FuncionesController;

$funciones = new FuncionesController();

use app\controllers\logerrorController;
$log = new logerrorController();



//Metodo POST para cargar  las boletas
if (isset($_GET['cargarboletas'])) {
    //se guardan los datos enviados del formulario
    $idformulario = $_GET['cargarboletas'];

    
    try {
        $result = $inscarga->obtenerboletas_por_idformulario($idformulario);
        $res = array(
            'status' => 200,
            'message' => 'carga usuarios correcta',
            'data' => $result
        );
    }
    catch (Exception  $e) {
       
            $log->guardarlog($e->getMessage());	
            $res = array(
                'status' => 404,
                'message' =>  'No se encontro informacion'
            );
    }

    
    echo json_encode($res);

 
}


//Metodo POST para cargar  las validaciones aplicadas al formulario
if (isset($_GET['cargarvalidaciones'])) {
    //se guardan los datos enviados del formulario
    $cargarvalidaciones = $_GET['cargarvalidaciones'];

    //llamada al metodo en el controlador para guardar o actualizar
    try{
        $result = $inscarga->obtenervalidacionesaplicadas_formulario($cargarvalidaciones);
        $res = array(
            'status' => 200,
            'message' => 'carga usuarios correcta',
            'data' => $result
        );
    }
    catch (Exception  $e) {
    
            $log->guardarlog($e->getMessage());	
            $res = array(
                'status' => 404,
                'message' =>  'No se encontro informacion'
            );
    }
    echo json_encode($res);
    
}


//Metodo GET para obtener los datos de las validaciones
if (isset($_GET['obtenerdatosvalidacion'])) {
    //se guardan los datos enviados del formulario
    $idvalidacion = $_GET['idvalidacion'];
    $boleta = $_GET['boleta'];

    //llamada al metodo en el controlador para guardar o actualizar
try{
    $result = $inscarga->OBETNERDATOSVALIDACION($idvalidacion, $boleta);
    $res = array(
        'status' => 200,
        'message' => 'carga datos correcta',
        'data' => $result
    );
}
catch (Exception  $e) {
    $log->guardarlog($e->getMessage());	  
    $res = array(
        'status' => 404,
        'message' =>  'No se encontro informacion'
    );
}

echo json_encode($res);

    
}

//Metodo GET para listar validaciones
if (isset($_GET['listarvalaciones'])) {
    //se guardan los datos enviados del formulario
    $cmb_formulario = $_GET['cmb_formulario'];

    try {
        //llamada al metodo en el controlador para guardar o actualizar

        // $result =$inscarga->listarvalidaciones_formulario($cmb_formulario);
        $result = $inscarga->listarvalidaciones_por_formulario($cmb_formulario);
        $res = array(
            'status' => 200,
            'message' => 'carga datos correcta',
            'data' => $result
        );
    }
    catch (Exception  $e) {
        $log->guardarlog($e->getMessage());	  
        $res = array(
            'status' => 404,
            'message' =>  'No se encontro informacion'
        );
    }


    echo json_encode($res);
    
}

if (isset($_POST['reporte'])) {
    $cmb_formulario = $_POST['cmb_formulario'];
   // $result = $inscarga->generarreportevalidaciones($cmb_formulario);


     $result = json_decode((string) json_encode(json_decode($_POST['datos'])), true);

    // extend TCPF with custom functions
    class MYPDF extends TCPDF
    {

        // Load table data from file
        public function LoadData($file)
        {
            // Read file lines

            //return $result;
        }



        // Colored table
        public function ColoredTable($header, $data)
        {
            $fecha = date('d-m-Y  H:i:s');

            $this->SetXY(150, 15); // Ajustar posición del texto
            $this->Cell(0, 10, $fecha, 0, 1, '');
            // Colors, line width and bold font
            $this->setFillColor(103, 182, 196);
            //$this->setTextColor(255);
            $this->setDrawColor(0, 0, 0);
            // $this->setLineWidth(0.1);
            $this->setFont('', 'B');
            // Header
            $w = array(70, 15, 30, 60);
            $num_headers = count($header);
            // for($i = 0; $i < $num_headers; ++$i) {
            //     $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
            // }
            // $this->Ln();
            // Color and font restoration
            $this->setFillColor(224, 235, 255);
            $this->setTextColor(0);
            $this->setFont('');

            //Parametros para las funciones Cell () y MultiCell()
            $fill = 1;
            $border = 'LTRB';
            $ln = 0;
            $fill = 0;
            $align = 'T';
            $link = 0;
            $stretch = 0;
            $ignore_min_height = 0;
            $calign = 'T';
            $valign = 'T';
            $height = 6; //alto de cada columna
            // Data
            $fill = 0;
            $fila = 0;
            $razon = "";


            // Colors, line width and bold font
            $this->setFillColor(103, 182, 196);
            // $this->setTextColor(255);
            $this->setDrawColor(0, 0, 0);
            $this->setLineWidth(0.1);
            $this->setFont('', 'B');
            // Header
            $w = array(50, 15, 30, 85);
            $num_headers = count($header);
            for ($i = 0; $i < $num_headers; ++$i) {
                $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
            }
            $this->Ln();

            foreach ($data as $row) {

                $ancho = 180;
                $altura = 9;
                $cantidad_lineas = strlen($row["validacion"]);
                if ($cantidad_lineas > $ancho) {
                    $cant_espacios = $cantidad_lineas / $ancho;
                    $rendondear = round($cant_espacios, 1);
                    $altura = $altura * $rendondear;
                }
                //Fin del iff
                //$this->Cell($w[0], 10, $row[0], 'LR', 0, 'L', $fill);
                //$this->Cell(30, $altura, $row["razonsocial"], $border,$ln,$align,$fill,$link,$stretch,$ignore_min_height,$calign,$valign);
                if ($razon != $row["validacion"]) {
                    //$this->Cell(array_sum($w), 0, '', 'T');
                    //$this->Ln();

                    // if ($fila!=0){
                    //     $this->Cell(array_sum($w), 0, '', 'T');
                    //     $this->Ln();
                    //     //$this->AddPage();
                    // }
                    // Colors, line width and bold font
                    // $this->setFillColor(103, 182, 196);
                    // // $this->setTextColor(255);
                    // $this->setDrawColor(0, 0, 0);
                    // // $this->setLineWidth(0.1);
                    // $this->setFont('', 'B');
                    // // Header
                    // $w = array(50, 15, 30,80);
                    // $num_headers = count($header);
                    // for($i = 0; $i < $num_headers; ++$i) {
                    //     $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
                    // }
                    //$this->Ln();
                    // Color and font restoration
                    $this->setFillColor(224, 235, 255);
                    $this->setTextColor(0);

                   $this->setFont('','B');
                    if ($this->GetY() + 40 > $this->getPageHeight() - $this->getMargins()['bottom']) {
                        $this->AddPage();
                        $this->SetXY(150, 15); // Ajustar posición del texto
                        $this->Cell(0, 10, $fecha, 0, 1, '');
                    }
                    
                    $this->MultiCell($ancho, $altura, $row["validacion"], $border, $align, $fill, $ln);
                    $this->setFont('');
                    $this->Ln();
                    $this->MultiCell($ancho, $altura, $row["formula"], $border, $align, $fill, $ln);
                    $razon = $row["validacion"];
                    $this->Ln();
                   
                }
                //  $this->setFont('');
                //     $this->MultiCell($ancho, $altura, "Discrepancia= ".sprintf("%.2f", $row["discrepancia"]), $border, $align, $fill, $ln);                    
                //     $this->Ln();
                $this->setFont('');
                $this->Cell(50, $altura,"Discrepancia= ".sprintf("%.2f", $row["discrepancia"]), $border, $ln, $align, $fill, $link, $stretch, $ignore_min_height, $calign, $valign);
                $this->Cell(15, $altura, $row["ciu"], $border, $ln, $align, $fill, $link, $stretch, $ignore_min_height, $calign, $valign);
                $this->Cell(30, $altura, $row["boleta"], $border, $ln, $align, $fill, $link, $stretch, $ignore_min_height, $calign, $valign);
                $ancho = 85;
                $altura = 9;
                $cantidad_lineas = strlen($row["razonsocial"]);
                if ($cantidad_lineas > $ancho) {
                    $cant_espacios = $cantidad_lineas / $ancho;
                    $rendondear = round($cant_espacios, 1);
                    $altura = $altura * $rendondear;
                }
                $this->MultiCell($ancho, $altura, reemplazarCaracteresEspeciales($row["razonsocial"]), $border, $align, $fill, $ln);
                $this->Ln();
                $fila = $fila + 1;
                $fill = !$fill;

                if ($this->GetY() + 25 > $this->getPageHeight() - $this->getMargins()['bottom']) {
                    $this->AddPage();
                    $this->SetXY(150, 15); // Ajustar posición del texto
                    $this->Cell(0, 10, $fecha, 0, 1, '');
                }
            }

            $this->Cell(array_sum($w), 0, '', 'T');
        }
    }

    // create new PDF document
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->setCreator(PDF_CREATOR);
    $pdf->setAuthor('BCN');
    $pdf->setTitle('Reporte Validaciones');
    $pdf->setSubject('validaciones');
    $pdf->setKeywords('TCPDF, PDF, example, test, guide');




    //$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' Validaciones', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
    //$pdf->setFooterData(array(0,64,0), array(0,64,128));

    // set default header data
    $pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' Validaciones', PDF_HEADER_STRING);
    // set header and footer fonts

    // set header and footer fonts
    $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // set default monospaced font
    $pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->setHeaderMargin(2);
    $pdf->setFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    //$pdf->setAutoPageBreak(TRUE,20);
    $pdf->setAutoPageBreak(FALSE);

    // set image scale factor
    //$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
        require_once(dirname(__FILE__) . '/lang/eng.php');
        $pdf->setLanguageArray($l);
    }

    // ---------------------------------------------------------

    // set font
    $pdf->setFont('helvetica', '', 10);

    // add a page
    $pdf->AddPage();


    //$pdf->Ln(5);
    // column titles
    $header = array('Validacion y formula', 'CIIU', 'Boleta', 'Razón Social');

    // data loading
    //$data = $pdf->LoadData('data/table_data_demo.txt');

    // print colored table
    $pdf->ColoredTable($header, $result);

    // ---------------------------------------------------------
    // Establecer cabeceras para que el navegador entienda que es un PDF
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="example_011.pdf"');
    // close and output PDF document
    $pdf->Output('example_011.pdf', 'I');

    //============================================================+
    // END OF FILE
    //============================================================+

}



//Metodo POST para el guardado de los registros
if (isset($_POST['modulo_Opcion'])) {
    try {
    if ($_POST['modulo_Opcion'] == "implementarvalidacion") {

        
        ini_set('max_execution_time', '0');
        ini_set('default_socket_timeout', 6000);
        set_time_limit(0);
        //ini_set('memory_limit', '1024'); 
        $validacionesnocumplen = array();
        $cmb_formulario = $_POST['cmb_formulario'];
        $cmb_boleta = $_POST['cmb_boleta'];

        if ($cmb_boleta == 0) {
            $validarboletas = $inscarga->obtenerboletas_por_idformulario($cmb_formulario);
            $cantidadboletas = count($validarboletas);
        } else {
            $validarboletas = $inscarga->obtenerboletas_por_boleta($cmb_boleta);
            $cantidadboletas = count($validarboletas);
        }



        $formulario = $inscarga->listarvalidaciones_formulario($cmb_formulario);
        $validaciones = count($formulario);

        for ($colbol = 0; $colbol <= $cantidadboletas - 1; ++$colbol) {

            $datoboleta = $validarboletas[$colbol];
            for ($col = 0; $col <= $validaciones - 1; ++$col) {

                $value = $formulario[$col];
                $tipovalidacion = $value['tipovalidacion'];
                $condicion = $value['condicion'];


                $parametros = $inscarga->obtenervalidacionparametros($cmb_formulario, $datoboleta['boleta'], $value['id_validacion']);
                if (!empty($parametros)) {
                    $contparam = count($parametros);
                    $param1 = 0;
                    $param2 = 0;
                    $param3 = 0;
                    for ($colpar = 0; $colpar <= $contparam - 1; ++$colpar) {


                        switch ($parametros[$colpar]['parametro']) {
                            case "1":
                                $param1 = round($inscarga->parseFloat(number_format($parametros[$colpar]['resultado'], 2, '.', '')), 2, PHP_ROUND_HALF_UP);
                                break;
                            case "2":
                                $param2 = round($inscarga->parseFloat(number_format($parametros[$colpar]['resultado'], 2, '.', '')), 2, PHP_ROUND_HALF_UP);
                                break;
                            case "3":
                                $param3 = round($inscarga->parseFloat(number_format($parametros[$colpar]['resultado'], 2, '.', '')), 2, PHP_ROUND_HALF_UP);
                                break;
                        }
                    }

                    $cumplevalidacion = "NO";
                    switch ($tipovalidacion) {
                        case "Validación_1":
                            if ((round($param1 - $param2, 2, PHP_ROUND_HALF_UP)) == 0) {
                                $cumplevalidacion = "SI";
                            } else {
                                $validacionesnocumplen[] = array(
                                    'id_validacion' => $value['id_validacion'],
                                    'id_formulario' => $cmb_formulario,
                                    'validacion' => $value['nombre'],
                                    'cumple' => 'NO',
                                    'tipovalidacion' => $tipovalidacion,
                                    'parametro1' => $param1,
                                    'parametro2' => $param2,
                                    'parametro3' => '',
                                    'discrepancia' => round($param1 - $param2, 2, PHP_ROUND_HALF_UP),
                                    'boleta' => $datoboleta['boleta'],
                                    'ciu' => $parametros[0]['ciu'],
                                    'rs' => $parametros[0]['razonsocial'],
                                );
                            }

                            break;
                        case "Validación_2":
                            $resta = $param2 - $param3;
                            if ((round($param1 - $resta, 2, PHP_ROUND_HALF_UP)) == 0) {
                                $cumplevalidacion = "SI";
                            } else {
                                $validacionesnocumplen[] = array(
                                    'id_validacion' => $value['id_validacion'],
                                    'id_formulario' => $cmb_formulario,
                                    'validacion' => $value['nombre'],
                                    'cumple' => 'NO',
                                    'tipovalidacion' => $tipovalidacion,
                                    'parametro1' => $param1,
                                    'parametro2' => $param2,
                                    'parametro3' => $param3,
                                    'discrepancia' => round($param1 - $resta, 2, PHP_ROUND_HALF_UP),
                                    'boleta' => $datoboleta['boleta'],
                                    'ciu' => $parametros[0]['ciu'],
                                    'rs' => $parametros[0]['razonsocial'],
                                );
                            }
                            break;
                        case "Validación_3":
                            if ($param1 > 0 && $param2 > 0) {
                                $cumplevalidacion = "SI";
                            } else {
                                $validacionesnocumplen[] = array(
                                    'id_validacion' => $value['id_validacion'],
                                    'id_formulario' => $cmb_formulario,
                                    'validacion' => $value['nombre'],
                                    'cumple' => 'NO',
                                    'tipovalidacion' => $tipovalidacion,
                                    'parametro1' => $param1,
                                    'parametro2' => $param2,
                                    'parametro3' => '',
                                    'discrepancia' => '',
                                    'boleta' => $datoboleta['boleta'],
                                    'ciu' => $parametros[0]['ciu'],
                                    'rs' => $parametros[0]['razonsocial'],
                                );
                            }
                            break;
                    }
                }
            }
        }
        if (!empty($validacionesnocumplen)) {
            $guardar = $inscarga->guardar(json_encode($validacionesnocumplen));
            $validaciones = $inscarga->obtenervalidacionesaplicadas_formulario($cmb_formulario);
            $alerta = [
                "status" => "200",
                "tipo" => "limpiar",
                "titulo" => "Implementar validacion",
                "texto" => "Existen validaciones que no cumplieron la condición parametrizada.",
                "icono" => "warning",
                "datos" => $validaciones
            ];
        } else {
            $alerta = [
                "status" => "200",
                "tipo" => "limpiar",
                "titulo" => "Implementar validacion",
                "texto" => "Las validaciones se procesaron correctamente",
                "icono" => "success",
                "datos" => "[]"
            ];
        }
    } else {
        $alerta = [
            "tipo" => "limpiar",
            "titulo" => "Implementar validacion",
            "texto" => "No se encontro información.",
            "icono" => "warning",
            "datos" => "[]"
        ];
    }
}
catch (Exception  $e) {
    $log->guardarlog($e->getMessage());	  
    $alerta = [
        "tipo" => "limpiar",
        "titulo" => "Implementar validacion",
        "texto" => "Ocurrio un error",
        "icono" => "error",
        "datos" => "[]"
    ];
}

    echo json_encode($alerta);
}



//Metodo POST para el guardado de los registros
if (isset($_POST['Cargaguardar']) == "guardar") {
    try {
    //se guardan los datos enviados del formulario
    $datos = $_POST['datos'];

    //llamada al metodo en el controlador para guardar o actualizar

    $result = $inscarga->guardar($datos);



    //resultado que se envia al metodo invocado
    if ($result) {
        $res = array(
            'status' => 200,
        );
        echo json_encode($res);
    } else {
        if (empty($result)) {
            $res = array(
                'status' => 200,
                'message' =>  'No se encontro informacion'
            );
        } else {
            $res = array(
                'status' => 404,
                'message' =>  'No se encontro informacion'
            );
        }

        echo json_encode($res);
    }
}
catch (Exception  $e) {
    $log->guardarlog($e->getMessage());	  
    $res = array(
        'status' => 404,
        'message' =>  'No se encontro informacion'
    );
    echo json_encode($res);
}

}

function reemplazarCaracteresEspeciales($texto) {
    // Mapeo de caracteres mal codificados a sus valores correctos
    $reemplazos = array(
        'Ã¡' => 'á', 'Ã¡' => 'á', 'Ã©' => 'é', 'Ã­' => 'í', 'Ã³' => 'ó', 'Ãº' => 'ú', 'Ã±' => 'ñ', 'Ã¿' => '¿', 'Ã‚' => 'Á',
        'Ã‘' => 'Ñ', 'Ã‚' => 'Á', 'Ã¡' => 'á', 'Ã©' => 'é', 'Ã­' => 'í', 'Ã³' => 'ó', 'Ãº' => 'ú', 'â' => 'á', '´'=> '´'
    );

    // Realizamos el reemplazo
    $texto_corregido = strtr($texto, $reemplazos);

    return $texto_corregido;
}