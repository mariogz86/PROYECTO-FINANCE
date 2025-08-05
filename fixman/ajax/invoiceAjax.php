<?php
require_once "../config/app.php";
require_once "../app/views/inc/session_start.php";
require_once('../tcpdf/examples/tcpdf_include.php');
require_once "../autoload.php";

//inicializar controlador
use app\controllers\invoiceController;

$insformulario = new invoiceController();

//Metodo GET para la carga del grid en la pantalla
if (isset($_GET['cargagrid'])) {
    //metodo del controlador
    $result = $insformulario->listar();
    //resultado que se envita al Metodo GET invocado
    if ($result) {
        $res = array(
            'status' => 200,
            'message' => 'Successful data upload',
            'data' => $result
        );
        echo json_encode($res);
    } else {
        $res = array(
            'status' => 404,
            'message' =>  'No information found'
        );
        echo json_encode($res);
    }
}

if (isset($_POST['generarreporteservicio'])) {
    $idtrabajo = $_POST['generarreporteservicio'];
    $reportes = $insformulario->obtenerreporteserviciosporidtrabajo($idtrabajo);
    $trabajo = $insformulario->obtenerjobporid($idtrabajo); 

class MYPDF extends TCPDF {

    public function Header() {
        // Mostrar solo en la primera página
        if ($this->page == 1) {
             // Definir posición, tamaño y ruta de la imagen
            $refri = '../tcpdf/examples/images/refri.jpg'; // Ruta de la imagen
            $x = 10;  // Posición X
            $y = 2;  // Posición Y
            $width = 25; // Ancho deseado
            $height = 40; // Alto deseado (ajustado automáticamente si se omite)

            // Agregar la imagen al encabezado
            $this->Image($refri, $x, $y, $width, $height);

            // Definir posición, tamaño y ruta de la imagen
            $labadora = '../tcpdf/examples/images/labadora.jpg'; // Ruta de la imagen
            $x = 175;  // Posición X
            $y = 3;  // Posición Y
            $width = 25; // Ancho deseado
            $height = 39; // Alto deseado (ajustado automáticamente si se omite)

            // Agregar la imagen al encabezado
            $this->Image($labadora, $x, $y, $width, $height);

            // Definir posición, tamaño y ruta de la imagen
            $titulo = '../tcpdf/examples/images/titulo.jpg'; // Ruta de la imagen
            $x = 50;  // Posición X
            $y = 3;  // Posición Y
            $width = 110; // Ancho deseado
            $height = 15; // Alto deseado (ajustado automáticamente si se omite)

            // Agregar la imagen al encabezado
            $this->Image($titulo, $x, $y, $width, $height);
 

            $this->setLineWidth(0.2);
            $this->Line(10, 45, 200, 45);
        }
    }

    public function Footer() {
        // Pie de página en todas las páginas
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, false, 'C');
    }
}

    // Crear nuevo PDF con TCPDF
$pdf = new MYPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('FIXMAN');
$pdf->SetTitle('Service Report');
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);

 // // Definir fuente para el título
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->SetXY(80, 18); // Ajustar posición del texto
    $pdf->Cell(0, 10, 'Technician\'s diagnosis', 0, 1, '');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->SetXY(93, 25); // Ajustar posición del texto
    $pdf->Cell(0, 10, $trabajo[0]["num_referencia"], 0, 1, '');
    $fecha = date('d-m-Y');

    $pdf->SetXY(96, 30); // Ajustar posición del texto
    $pdf->Cell(0, 10, $fecha, 0, 1, '');

$pdf->SetXY(15, 25);
// Título
//$pdf->Write(0, 'Reporte de Servicio', '', 0, 'C', true, 0, false, false, 0);
//$pdf->Ln(5);

$pdf->SetXY(10, 50);


// Iterar registros
foreach ($reportes as $reporte) {
    $pdf->SetFont('', 'B');
    $pdf->Write(0, "Appliance: " . $reporte['appliance']." ----- Brand: " . $reporte['brand'], '', 0, 'L', true, 0, false, false, 0);
    $pdf->SetFont('', '');

    // Tabla de datos principales
    $html = '<table border="1" cellpadding="4">
        <tr><th>Model</th><td>' . $reporte['model'] . '</td></tr>
        <tr><th>Serial</th><td>' . $reporte['serial'] . '</td></tr>
        <tr><th>Describe the problem</th><td>' . $reporte['problemdetail'] . '</td></tr>
        <tr><th>Labor Cost</th><td>' . $reporte['laborcost'] . '</td></tr>
        <tr><th>Type of Power Cord</th><td>' . $reporte['tipocable'] . '</td></tr>
        <tr><th>Other</th><td>' . $reporte['otrotipocable'] . '</td></tr>
        <tr><th>Potential factors</th><td>' . $reporte['falla'] . '</td></tr>
        <tr><th>Other</th><td>' . $reporte['otrofactorfalla'] . '</td></tr>
    </table><br>';

    $pdf->writeHTML($html, true, false, false, false, '');

    // Detalle del campo JSONB: datopartes
    if (!empty($reporte['datopartes'])) {
        $partes = json_decode($reporte['datopartes'], true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($partes)) {
            $pdf->SetFont('', 'B');
            $pdf->Write(0, 'Detail of parts to be repaired', '', 0, 'L', true, 0, false, false, 0);
            $pdf->SetFont('', '');

            $html = '<table border="1" cellpadding="4">
                <thead>
                    <tr style="background-color:#cccccc;">
                        <th>Part Name</th>
                        <th>Part Number</th>
                        <th>Part Price</th>
                        <th>Quantity</th>
                    </tr>
                </thead><tbody>';

            foreach ($partes as $parte) {
                $html .= '<tr>
                <td>' . htmlspecialchars($parte['cantidad']) . '</td>
                    <td>' . htmlspecialchars($parte['nombre']) . '</td>
                    <td>' . htmlspecialchars($parte['numero']) . '</td>
                    <td>' . htmlspecialchars($parte['precio']) . '</td>                    
                </tr>';
            }

            $html .= '</tbody></table><br>';
            $pdf->writeHTML($html, true, false, false, false, '');
        } else {
            $pdf->Write(0, 'Error al decodificar el campo JSON.', '', 0, 'L', true, 0, false, false, 0);
        }
    } else {
        $pdf->Write(0, 'No parts were registered.', '', 0, 'L', true, 0, false, false, 0);
    }

    $pdf->Ln(5); // Espacio entre reportes
}

// Salida del PDF
$pdf->Output('reporte_servicio.pdf', 'I');

}

if (isset($_POST['generarfactura'])) {
    $idtrabajo = $_POST['generarfactura'];
    $trabajo = $insformulario->obtenerjobporid($idtrabajo);
    $servicios = $insformulario->obtenerserviciosporidtrabajo($idtrabajo);
    $diagnosticos = $insformulario->obtenerdiagnosticos($idtrabajo);
    $partes = $insformulario->obtenerpartes($idtrabajo);



    // $result = json_decode((string) json_encode(json_decode($_POST['datos'])), true);

    // extend TCPF with custom functions
    class MYPDF extends TCPDF
    {
        // Load table data from file
        public function LoadData($file)
        {
            // Read file lines

            //return $result;
        }

        // Personalizar el encabezado
        public function Header()
        {
            if ($this->getPage() == 1) { 
           
            // Definir posición, tamaño y ruta de la imagen
            $refri = '../tcpdf/examples/images/refri.jpg'; // Ruta de la imagen
            $x = 10;  // Posición X
            $y = 2;  // Posición Y
            $width = 25; // Ancho deseado
            $height = 40; // Alto deseado (ajustado automáticamente si se omite)

            // Agregar la imagen al encabezado
            $this->Image($refri, $x, $y, $width, $height);

            // Definir posición, tamaño y ruta de la imagen
            $labadora = '../tcpdf/examples/images/labadora.jpg'; // Ruta de la imagen
            $x = 175;  // Posición X
            $y = 3;  // Posición Y
            $width = 25; // Ancho deseado
            $height = 39; // Alto deseado (ajustado automáticamente si se omite)

            // Agregar la imagen al encabezado
            $this->Image($labadora, $x, $y, $width, $height);

            // Definir posición, tamaño y ruta de la imagen
            $titulo = '../tcpdf/examples/images/titulo.jpg'; // Ruta de la imagen
            $x = 50;  // Posición X
            $y = 3;  // Posición Y
            $width = 110; // Ancho deseado
            $height = 15; // Alto deseado (ajustado automáticamente si se omite)

            // Agregar la imagen al encabezado
            $this->Image($titulo, $x, $y, $width, $height);

            // // Definir fuente para el título
            // $this->SetFont('helvetica', 'B', 12);
            // $this->SetXY(100, 15); // Ajustar posición del texto
            // $this->Cell(0, 10, 'Invoice Information', 0, 1, 'L');

            // // Subtítulo opcional
            // $this->SetFont('helvetica', '', 10);
            // $this->SetXY(100, 20);
            // $this->Cell(60, 10, 'Subtítulo o información adicional', 0, 1, 'L');

            $this->setLineWidth(0.2);
            $this->Line(10, 45, 200, 45);
        } 
        }

        // Colored table
        public function ColoredTable($datos, $servicios, $diagnosticos, $partes)
        {
            
            $this->SetXY(15, 50);
            // Información de la empresa
            $this->SetFont('helvetica', '', 10);
            $this->Cell(0, 5, $datos[0]["nombre"], 0, 1, 'L');
            $this->Cell(0, 5, $datos[0]["full_name"], 0, 1, 'L');
            $this->Cell(0, 5, $datos[0]["address"], 0, 1, 'L');
            $this->Cell(0, 5, 'Phone: ' . $datos[0]["phone"], 0, 1, 'L');
            $this->Cell(0, 5, 'Email: ' . $datos[0]["email"], 0, 1, 'L');
            $this->Ln(5);

            // Información del cliente
            // $this->Cell(0, 5, 'Cliente: Juan Pérez', 0, 1, 'L');
            // $this->Cell(0, 5, 'Dirección: Av. Cliente 456', 0, 1, 'L');
            // $this->Cell(0, 5, 'Teléfono: +987654321', 0, 1, 'L');
            // $this->Ln(5);

            // Tabla de productos
            $this->setFillColor(224, 235, 255);

            $this->SetFont('helvetica', 'B', 12);
            $this->Cell(60, 7, 'Service', 'B', 0, 'L', 1);
            $this->Cell(50, 7, 'Appliance', 'B', 0, 'L', 1);
            $this->Cell(40, 7, 'Model', 'B', 0, 'L', 1);
            $this->Cell(35, 7, 'Amount', 'B', 1, 'L', 1);

            $this->SetFont('helvetica', '', 10);


            $total1 = 0;
            foreach ($servicios as $producto) {
                $subtotal = $producto['servicefee'];
                $total1 += $subtotal;
                $this->Cell(60, 7, $producto['tiposervicio'], 'B', 0, 'L');
                $this->Cell(50, 7, $producto['appliance'], 'B', 0, 'L');
                $this->Cell(40, 7, $producto['model'], 'B', 0, 'L');
                $this->Cell(35, 7, '$ ' . number_format($producto['servicefee'], 2), 'B', 1, 'L');
                //$this->Cell(40, 7, '$' . number_format($subtotal, 2), 1, 1, 'C');
            }

            // Total
            $this->SetFont('helvetica', 'B', 10);
            $this->Cell(150, 7, 'Total:', 'B', 0, 'R');
            $this->Cell(35, 7, '$ ' . number_format($total1, 2), 'B', 1, 'L');


            $this->Ln();
            // Tabla de diagnosticos
            $this->SetFont('helvetica', 'B', 12);
            $this->Cell(185, 7, 'Diagnosis', 'B', 1, 'C', 1);
            $this->Cell(60, 7, 'Appliance', 'B', 0, 'L', 1);
            $this->Cell(90, 7, 'Serial #', 'B', 0, 'L', 1);
            $this->Cell(35, 7, 'Labor Fee', 'B', 1, 'L', 1);

            $this->SetFont('helvetica', '', 10);
            $total2 = 0;
            foreach ($diagnosticos as $producto) {
                $subtotal = $producto['laborfee'];
                $total2 += $subtotal;
                $this->Cell(60, 7, $producto['appliance'], 'B', 0, 'L');
                $this->Cell(90, 7, $producto['serial'], 'B', 0, 'L');
                $this->Cell(35, 7, '$ ' . number_format($producto['laborfee'], 2), 'B', 1, 'L');
                $this->Ln(1);
                $this->writeHTMLCell(185, 7, '', '', '<b>Description of work done:</b> ' . $producto['nota'], 'B', 1, false, true, 'L');
            }

            // Total
            $this->SetFont('helvetica', 'B', 10);
            $this->Cell(150, 7, 'Total:', 'B', 0, 'R');
            $this->Cell(35, 7, '$ ' . number_format($total2, 2), 'B', 1, 'L');

            $this->Ln();
            // Tabla de diagnosticos
            $this->SetFont('helvetica', 'B', 12);
            $this->Cell(185, 7, 'Parts', 'B', 1, 'C', 1);
            $this->Cell(50, 7, 'Appliance', 'B', 0, 'L', 1);
            $this->Cell(40, 7, 'Part name', 'B', 0, 'L', 1);
            $this->Cell(40, 7, 'Serial', 'B', 0, 'R', 1);
            $this->Cell(20, 7, 'Qty', 'B', 0, 'L', 1);
            $this->Cell(35, 7, 'Amount', 'B', 1, 'L', 1);

            $this->SetFont('helvetica', '', 10);
            $total3 = 0;
            foreach ($partes as $producto) {
                $subtotal = $producto['amount'];
                $total3 += $subtotal;
                $this->Cell(50, 7, $producto['appliance'], 'B', 0, 'L');
                $this->Cell(40, 7, $producto['nombre'], 'B', 0, 'L');
                $this->Cell(40, 7, $producto['serial'], 'B', 0, 'R');
                $this->Cell(20, 7, $producto['cantidad'], 'B', 0, 'L');
                $this->Cell(35, 7, '$ ' . number_format($producto['amount'], 2), 'B', 1, 'L');
            }

            // Total
            $this->SetFont('helvetica', 'B', 10);
            $this->Cell(150, 7, 'Total:', 'B', 0, 'R');
            $this->Cell(35, 7, '$ ' . number_format($total3, 2), 'B', 1, 'L');


            $this->Ln();
            // GRAN Total
            $total = $total1 + $total2 + $total3;
            $this->SetFont('helvetica', 'B', 10);
            $this->Cell(150, 7, 'Grand Total:', 'T B', 0, 'R');
            $this->Cell(35, 7, '$ ' . number_format($total, 2), 'T B', 1, 'L');
        }
    }

    // create new PDF document
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->setCreator(PDF_CREATOR);
    $pdf->setAuthor('FIXMAN');
    $pdf->setTitle('Report');
    $pdf->setSubject('invoice');
    $pdf->setKeywords('TCPDF, PDF, example, test, guide');


    //$pdf->setHeaderData(PDF_HEADER_LOGO, 45, PDF_HEADER_TITLE . ' Invoice', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
    //$pdf->setHeaderData(PDF_HEADER_LOGO, 45, PDF_HEADER_TITLE . ' Invoice', PDF_HEADER_STRING);
    //$pdf->setFooterData(array(0,64,0), array(0,64,128));

    // set default header data
    //$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' Validaciones', PDF_HEADER_STRING);
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
    $pdf->setAutoPageBreak(TRUE, 10);
    //$pdf->setAutoPageBreak(FALSE);

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
    //$header = array('Validacion y formula', 'CIIU', 'Boleta', 'Razón Social');

    // data loading
    // // Definir fuente para el título
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->SetXY(88, 18); // Ajustar posición del texto
    $pdf->Cell(0, 10, 'Invoice Information', 0, 1, '');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->SetXY(93, 25); // Ajustar posición del texto
    $pdf->Cell(0, 10, $trabajo[0]["num_referencia"], 0, 1, '');
    $fecha = date('d-m-Y');

    $pdf->SetXY(96, 30); // Ajustar posición del texto
    $pdf->Cell(0, 10, $fecha, 0, 1, '');
    // print colored table
    $pdf->ColoredTable($trabajo, $servicios,  $diagnosticos, $partes);

    // ---------------------------------------------------------
    // Establecer cabeceras para que el navegador entienda que es un PDF
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="example_011.pdf"');
    // close and output PDF document
    $pdf->Output('factura.pdf', 'I');

    

    //============================================================+
    // END OF FILE
    //============================================================+

}


if (isset($_POST['enviarfactura'])) {
    $idtrabajo = $_POST['enviarfactura'];
    $trabajo = $insformulario->obtenerjobporid($idtrabajo);
    $servicios = $insformulario->obtenerserviciosporidtrabajo($idtrabajo);
    $diagnosticos = $insformulario->obtenerdiagnosticos($idtrabajo);
    $partes = $insformulario->obtenerpartes($idtrabajo);



    // $result = json_decode((string) json_encode(json_decode($_POST['datos'])), true);

    // extend TCPF with custom functions
    class MYPDF extends TCPDF
    {
        // Load table data from file
        public function LoadData($file)
        {
            // Read file lines

            //return $result;
        }

        // Personalizar el encabezado
        public function Header()
        {
            if ($this->getPage() == 1) { 
           
            // Definir posición, tamaño y ruta de la imagen
            $refri = '../tcpdf/examples/images/refri.jpg'; // Ruta de la imagen
            $x = 10;  // Posición X
            $y = 2;  // Posición Y
            $width = 25; // Ancho deseado
            $height = 40; // Alto deseado (ajustado automáticamente si se omite)

            // Agregar la imagen al encabezado
            $this->Image($refri, $x, $y, $width, $height);

            // Definir posición, tamaño y ruta de la imagen
            $labadora = '../tcpdf/examples/images/labadora.jpg'; // Ruta de la imagen
            $x = 175;  // Posición X
            $y = 3;  // Posición Y
            $width = 25; // Ancho deseado
            $height = 39; // Alto deseado (ajustado automáticamente si se omite)

            // Agregar la imagen al encabezado
            $this->Image($labadora, $x, $y, $width, $height);

            // Definir posición, tamaño y ruta de la imagen
            $titulo = '../tcpdf/examples/images/titulo.jpg'; // Ruta de la imagen
            $x = 50;  // Posición X
            $y = 3;  // Posición Y
            $width = 110; // Ancho deseado
            $height = 15; // Alto deseado (ajustado automáticamente si se omite)

            // Agregar la imagen al encabezado
            $this->Image($titulo, $x, $y, $width, $height);

            // // Definir fuente para el título
            // $this->SetFont('helvetica', 'B', 12);
            // $this->SetXY(100, 15); // Ajustar posición del texto
            // $this->Cell(0, 10, 'Invoice Information', 0, 1, 'L');

            // // Subtítulo opcional
            // $this->SetFont('helvetica', '', 10);
            // $this->SetXY(100, 20);
            // $this->Cell(60, 10, 'Subtítulo o información adicional', 0, 1, 'L');

            $this->setLineWidth(0.2);
            $this->Line(10, 45, 200, 45);
        } 
        }

        // Colored table
        public function ColoredTable($datos, $servicios, $diagnosticos, $partes)
        {
            
            $this->SetXY(15, 50);
            // Información de la empresa
            $this->SetFont('helvetica', '', 10);
            $this->Cell(0, 5, $datos[0]["nombre"], 0, 1, 'L');
            $this->Cell(0, 5, $datos[0]["full_name"], 0, 1, 'L');
            $this->Cell(0, 5, $datos[0]["address"], 0, 1, 'L');
            $this->Cell(0, 5, 'Phone: ' . $datos[0]["phone"], 0, 1, 'L');
            $this->Cell(0, 5, 'Email: ' . $datos[0]["email"], 0, 1, 'L');
            $this->Ln(5);

            // Información del cliente
            // $this->Cell(0, 5, 'Cliente: Juan Pérez', 0, 1, 'L');
            // $this->Cell(0, 5, 'Dirección: Av. Cliente 456', 0, 1, 'L');
            // $this->Cell(0, 5, 'Teléfono: +987654321', 0, 1, 'L');
            // $this->Ln(5);

            // Tabla de productos
            $this->setFillColor(224, 235, 255);

            $this->SetFont('helvetica', 'B', 12);
            $this->Cell(60, 7, 'Service', 'B', 0, 'L', 1);
            $this->Cell(50, 7, 'Appliance', 'B', 0, 'L', 1);
            $this->Cell(40, 7, 'Model', 'B', 0, 'L', 1);
            $this->Cell(35, 7, 'Amount', 'B', 1, 'L', 1);

            $this->SetFont('helvetica', '', 10);


            $total1 = 0;
            foreach ($servicios as $producto) {
                $subtotal = $producto['servicefee'];
                $total1 += $subtotal;
                $this->Cell(60, 7, $producto['tiposervicio'], 'B', 0, 'L');
                $this->Cell(50, 7, $producto['appliance'], 'B', 0, 'L');
                $this->Cell(40, 7, $producto['model'], 'B', 0, 'L');
                $this->Cell(35, 7, '$ ' . number_format($producto['servicefee'], 2), 'B', 1, 'L');
                //$this->Cell(40, 7, '$' . number_format($subtotal, 2), 1, 1, 'C');
            }

            // Total
            $this->SetFont('helvetica', 'B', 10);
            $this->Cell(150, 7, 'Total:', 'B', 0, 'R');
            $this->Cell(35, 7, '$ ' . number_format($total1, 2), 'B', 1, 'L');


            $this->Ln();
            // Tabla de diagnosticos
            $this->SetFont('helvetica', 'B', 12);
            $this->Cell(185, 7, 'Diagnosis', 'B', 1, 'C', 1);
            $this->Cell(60, 7, 'Appliance', 'B', 0, 'L', 1);
            $this->Cell(90, 7, 'Serial #', 'B', 0, 'L', 1);
            $this->Cell(35, 7, 'Labor Fee', 'B', 1, 'L', 1);

            $this->SetFont('helvetica', '', 10);
            $total2 = 0;
            foreach ($diagnosticos as $producto) {
                $subtotal = $producto['laborfee'];
                $total2 += $subtotal;
                $this->Cell(60, 7, $producto['appliance'], 'B', 0, 'L');
                $this->Cell(90, 7, $producto['serial'], 'B', 0, 'L');
                $this->Cell(35, 7, '$ ' . number_format($producto['laborfee'], 2), 'B', 1, 'L');
                $this->Ln(1);
                $this->writeHTMLCell(185, 7, '', '', '<b>Description of work done:</b> ' . $producto['nota'], 'B', 1, false, true, 'L');
            }

            // Total
            $this->SetFont('helvetica', 'B', 10);
            $this->Cell(150, 7, 'Total:', 'B', 0, 'R');
            $this->Cell(35, 7, '$ ' . number_format($total2, 2), 'B', 1, 'L');

            $this->Ln();
            // Tabla de diagnosticos
            $this->SetFont('helvetica', 'B', 12);
            $this->Cell(185, 7, 'Parts', 'B', 1, 'C', 1);
            $this->Cell(50, 7, 'Appliance', 'B', 0, 'L', 1);
            $this->Cell(40, 7, 'Part name', 'B', 0, 'L', 1);
            $this->Cell(40, 7, 'Serial', 'B', 0, 'R', 1);
            $this->Cell(20, 7, 'Qty', 'B', 0, 'L', 1);
            $this->Cell(35, 7, 'Amount', 'B', 1, 'L', 1);

            $this->SetFont('helvetica', '', 10);
            $total3 = 0;
            foreach ($partes as $producto) {
                $subtotal = $producto['amount'];
                $total3 += $subtotal;
                $this->Cell(50, 7, $producto['appliance'], 'B', 0, 'L');
                $this->Cell(40, 7, $producto['nombre'], 'B', 0, 'L');
                $this->Cell(40, 7, $producto['serial'], 'B', 0, 'R');
                $this->Cell(20, 7, $producto['cantidad'], 'B', 0, 'L');
                $this->Cell(35, 7, '$ ' . number_format($producto['amount'], 2), 'B', 1, 'L');
            }

            // Total
            $this->SetFont('helvetica', 'B', 10);
            $this->Cell(150, 7, 'Total:', 'B', 0, 'R');
            $this->Cell(35, 7, '$ ' . number_format($total3, 2), 'B', 1, 'L');


            $this->Ln();
            // GRAN Total
            $total = $total1 + $total2 + $total3;
            $this->SetFont('helvetica', 'B', 10);
            $this->Cell(150, 7, 'Grand Total:', 'T B', 0, 'R');
            $this->Cell(35, 7, '$ ' . number_format($total, 2), 'T B', 1, 'L');
        }
    }

    // create new PDF document
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->setCreator(PDF_CREATOR);
    $pdf->setAuthor('FIXMAN');
    $pdf->setTitle('Report');
    $pdf->setSubject('invoice');
    $pdf->setKeywords('TCPDF, PDF, example, test, guide');


    //$pdf->setHeaderData(PDF_HEADER_LOGO, 45, PDF_HEADER_TITLE . ' Invoice', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
    //$pdf->setHeaderData(PDF_HEADER_LOGO, 45, PDF_HEADER_TITLE . ' Invoice', PDF_HEADER_STRING);
    //$pdf->setFooterData(array(0,64,0), array(0,64,128));

    // set default header data
    //$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' Validaciones', PDF_HEADER_STRING);
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
    $pdf->setAutoPageBreak(TRUE, 10);
    //$pdf->setAutoPageBreak(FALSE);

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
    //$header = array('Validacion y formula', 'CIIU', 'Boleta', 'Razón Social');

    // data loading
    // // Definir fuente para el título
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->SetXY(88, 18); // Ajustar posición del texto
    $pdf->Cell(0, 10, 'Invoice Information', 0, 1, '');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->SetXY(93, 25); // Ajustar posición del texto
    $pdf->Cell(0, 10, $trabajo[0]["num_referencia"], 0, 1, '');
    $fecha = date('d-m-Y');

    $pdf->SetXY(96, 30); // Ajustar posición del texto
    $pdf->Cell(0, 10, $fecha, 0, 1, '');
    // print colored table
    $pdf->ColoredTable($trabajo, $servicios,  $diagnosticos, $partes);

    // ---------------------------------------------------------
    // Establecer cabeceras para que el navegador entienda que es un PDF
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="example_011.pdf"');
    // close and output PDF document 
    $pdf = $pdf->Output('', 'S'); // 'S' devuelve el contenido en una variable

    
    $envio = $insformulario->usuariocreado( $trabajo[0]["correocompany"],$trabajo[0]["num_referencia"],$pdf);
    //============================================================+
    // END OF FILE
    //============================================================+

    //metodo del controlador 
		//resultado que se envita al Metodo GET invocado
		if($envio==1 )
		{ 
			$res = array (
				'status' => 200,
				'message' => 'Successful data upload',
				'data' => ''
					);
			echo json_encode($res);
		}
		else
		{
			$res = array (
				'status' => 404,
				'message' =>  'No information found'
				);
			echo json_encode($res); 
		} 

}
