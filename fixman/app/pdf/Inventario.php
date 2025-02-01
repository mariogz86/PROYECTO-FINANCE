<?php
	
	$peticion_ajax=true; 
	$idempresa=(isset($_GET['idempresa'])) ? $_GET['idempresa'] : 0;
	$usuario=(isset($_GET['usuario'])) ? $_GET['usuario'] : '';

	/*---------- Incluyendo configuraciones ----------*/
	require_once "../../config/app.php";
    require_once "../../autoload.php";

	/*---------- Instancia al controlador venta ----------*/
	use app\controllers\productController;
	$ins_venta = new productController();

	$datos_venta=$ins_venta->seleccionarDatos("Normal","producto  WHERE (empresa_id ='$idempresa') order by producto_stock_total asc","*",0);
	
	$check_usuario=$ins_venta->seleccionarDatos("Normal","usuario u inner join usuario_empresa ue on u.usuario_id=ue.usuario_id 
	inner join roles r on r.id_rol=u.id_rol
	WHERE usuario_usuario='$usuario'","*",0);

	if($datos_venta->rowCount()>0){

		/*---------- Datos de la venta ----------*/
		//$datos_venta=$datos_venta->fetch();
		$check_usuario=$check_usuario->fetch();

		/*---------- Seleccion de datos de la empresa ----------*/
		$datos_empresa=$ins_venta->seleccionarDatos("Unico","empresa","empresa_id",$idempresa);
		$datos_empresa=$datos_empresa->fetch();
		$venta_fecha=date("Y-m-d");
        $venta_hora=date("h:i a");


		require "./code128.php";

		$pdf = new PDF_Code128('P','mm','Letter');
		$pdf->SetMargins(17,17,17);
		$pdf->AddPage();
		$pdf->Image(APP_URL.'app/views/fotos/'.$datos_empresa['empresa_foto'],165,12,35,35);

		$pdf->SetFont('Arial','B',16);
		$pdf->SetTextColor(32,100,210);
		$pdf->Cell(150,10,iconv("UTF-8", "ISO-8859-1",strtoupper($datos_empresa['empresa_nombre'])),0,0,'L');

		$pdf->Ln(9);

		$pdf->SetFont('Arial','B',15);
		$pdf->SetTextColor(39,39,51);
		$pdf->Cell(0,0,iconv("UTF-8", "ISO-8859-1","Listado de Inventario"),0,0,'C');
		$pdf->SetFont('Arial','',10);
		$pdf->Ln(5);

		$pdf->Cell(150,9,iconv("UTF-8", "ISO-8859-1",$datos_empresa['empresa_direccion']),0,0,'L');

		$pdf->Ln(5);

		$pdf->Cell(150,9,iconv("UTF-8", "ISO-8859-1","Teléfono: ".$datos_empresa['empresa_telefono']),0,0,'L');

		$pdf->Ln(5);

		$pdf->Cell(150,9,iconv("UTF-8", "ISO-8859-1","Email: ".$datos_empresa['empresa_email']),0,0,'L');

		$pdf->Ln(10);

		$pdf->SetFont('Arial','',10);
		$pdf->Cell(12,7,iconv("UTF-8", "ISO-8859-1",'Fecha:'),0,0);
		$pdf->SetTextColor(97,97,97);
		$pdf->Cell(136,7,iconv("UTF-8", "ISO-8859-1",$venta_fecha." ".$venta_hora),0,0,'L');
		$pdf->SetFont('Arial','B',10);
		$pdf->SetTextColor(39,39,51);
		$pdf->Cell(40,7,iconv("UTF-8", "ISO-8859-1",strtoupper('rol')),0,0,'C');

		$pdf->Ln(7);

		$pdf->SetFont('Arial','',10);
		$pdf->Cell(15,7,iconv("UTF-8", "ISO-8859-1",'Usuario:'),0,0,'L');
		$pdf->SetTextColor(97,97,97);
		$pdf->Cell(145,7,iconv("UTF-8", "ISO-8859-1",$check_usuario['usuario_nombre']." ".$check_usuario['usuario_apellido']),0,0,'L');
		$pdf->SetFont('Arial','B',10);
		$pdf->SetTextColor(97,97,97);
		$pdf->Cell(1,7,iconv("UTF-8", "ISO-8859-1",strtoupper($check_usuario['rol'])),0,0,'C');

		$pdf->Ln(9);
		$pdf->SetFont('Arial','B',12);

		$pdf->SetFillColor(23,83,201);
		$pdf->SetDrawColor(23,83,201);
		$pdf->SetTextColor(255,255,255);
		$pdf->Cell(65,8,iconv("UTF-8", "ISO-8859-1",'Codigo'),1,0,'C',true);
		$pdf->Cell(80,8,iconv("UTF-8", "ISO-8859-1",'Nombre'),1,0,'C',true);
		$pdf->Cell(35,8,iconv("UTF-8", "ISO-8859-1",'Cantidad'),1,0,'C',true); 

		$pdf->Ln(8);

		$pdf->SetFont('Arial','',12);
		$pdf->SetTextColor(39,39,51);

		/*----------  listado de productos  ----------*/		
		$venta_detalle=$datos_venta->fetchAll();
		$altofila =7;
		foreach($venta_detalle as $detalle){
			
			$pdf->Cell(65,$altofila,iconv("UTF-8", "ISO-8859-1",$detalle['producto_codigo']),'L',0,'C');
			$pdf->Cell(80,$altofila,iconv("UTF-8", "ISO-8859-1",$detalle['producto_nombre']),'L',0,'C');
			$pdf->Cell(35,$altofila,iconv("UTF-8", "ISO-8859-1",$detalle['producto_stock_total']),'LR',0,'C'); 
			// $pdf->SetFillColor(39,39,51);
			// $pdf->SetDrawColor(23,83,201);
			// $pdf->Code128(150,$pdf->GetY(),$detalle['producto_codigo'],50,3);
			// $pdf->SetXY(10,$pdf->GetY()+2);
			// $pdf->SetFont('Arial','',12);
			// $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1",$detalle['producto_codigo']),0,'C',false);
			
			$pdf->Ln(7);
			
		}
 
		
		$pdf->Cell(180,7,iconv("UTF-8", "ISO-8859-1",''),'T',0,'C');  

		// $pdf->Ln(10);

		// if($datos_venta['cliente_id']==1){
		// 	$pdf->SetFont('Arial','',10);
		// 	$pdf->SetTextColor(39,39,51);
		// 	$pdf->Cell(13,7,iconv("UTF-8", "ISO-8859-1",'Cliente:'),0,0);
		// 	$pdf->SetTextColor(97,97,97);
		// 	$pdf->Cell(60,7,iconv("UTF-8", "ISO-8859-1","N/A"),0,0,'L');
		// 	$pdf->SetTextColor(39,39,51);
		// 	$pdf->Cell(8,7,iconv("UTF-8", "ISO-8859-1","Doc: "),0,0,'L');
		// 	$pdf->SetTextColor(97,97,97);
		// 	$pdf->Cell(60,7,iconv("UTF-8", "ISO-8859-1","N/A"),0,0,'L');
		// 	$pdf->SetTextColor(39,39,51);
		// 	$pdf->Cell(7,7,iconv("UTF-8", "ISO-8859-1",'Tel:'),0,0,'L');
		// 	$pdf->SetTextColor(97,97,97);
		// 	$pdf->Cell(35,7,iconv("UTF-8", "ISO-8859-1","N/A"),0,0);
		// 	$pdf->SetTextColor(39,39,51);

		// 	$pdf->Ln(7);

		// 	$pdf->SetTextColor(39,39,51);
		// 	$pdf->Cell(6,7,iconv("UTF-8", "ISO-8859-1",'Dir:'),0,0);
		// 	$pdf->SetTextColor(97,97,97);
		// 	$pdf->Cell(109,7,iconv("UTF-8", "ISO-8859-1","N/A"),0,0);
		// }else{
		// 	$pdf->SetFont('Arial','',10);
		// 	$pdf->SetTextColor(39,39,51);
		// 	$pdf->Cell(13,7,iconv("UTF-8", "ISO-8859-1",'Cliente:'),0,0);
		// 	$pdf->SetTextColor(97,97,97);
		// 	$pdf->Cell(60,7,iconv("UTF-8", "ISO-8859-1",$datos_venta['cliente_nombre']." ".$datos_venta['cliente_apellido']),0,0,'L');
		// 	$pdf->SetTextColor(39,39,51);
		// 	$pdf->Cell(8,7,iconv("UTF-8", "ISO-8859-1","Doc: "),0,0,'L');
		// 	$pdf->SetTextColor(97,97,97);
		// 	$pdf->Cell(60,7,iconv("UTF-8", "ISO-8859-1",$datos_venta['cliente_tipo_documento']." ".$datos_venta['cliente_numero_documento']),0,0,'L');
		// 	$pdf->SetTextColor(39,39,51);
		// 	$pdf->Cell(7,7,iconv("UTF-8", "ISO-8859-1",'Tel:'),0,0,'L');
		// 	$pdf->SetTextColor(97,97,97);
		// 	$pdf->Cell(35,7,iconv("UTF-8", "ISO-8859-1",$datos_venta['cliente_telefono']),0,0);
		// 	$pdf->SetTextColor(39,39,51);

		// 	$pdf->Ln(7);

		// 	$pdf->SetTextColor(39,39,51);
		// 	$pdf->Cell(6,7,iconv("UTF-8", "ISO-8859-1",'Dir:'),0,0);
		// 	$pdf->SetTextColor(97,97,97);
		// 	$pdf->Cell(109,7,iconv("UTF-8", "ISO-8859-1",$datos_venta['cliente_provincia'].", ".$datos_venta['cliente_ciudad'].", ".$datos_venta['cliente_direccion']),0,0);
		// }

		// $pdf->Ln(9);

		// $pdf->SetFillColor(23,83,201);
		// $pdf->SetDrawColor(23,83,201);
		// $pdf->SetTextColor(255,255,255);
		// $pdf->Cell(100,8,iconv("UTF-8", "ISO-8859-1",'Descripción'),1,0,'C',true);
		// $pdf->Cell(15,8,iconv("UTF-8", "ISO-8859-1",'Cant.'),1,0,'C',true);
		// $pdf->Cell(32,8,iconv("UTF-8", "ISO-8859-1",'Precio'),1,0,'C',true);
		// $pdf->Cell(34,8,iconv("UTF-8", "ISO-8859-1",'Subtotal'),1,0,'C',true);

		// $pdf->Ln(8);

		// $pdf->SetFont('Arial','',9);
		// $pdf->SetTextColor(39,39,51);

		/*----------  Seleccionando detalles de la venta  ----------*/
		// $venta_detalle=$ins_venta->seleccionarDatos("Normal","venta_detalle WHERE venta_codigo='".$datos_venta['venta_codigo']."'","*",0);
		// $venta_detalle=$venta_detalle->fetchAll();

		// foreach($venta_detalle as $detalle){
		// 	$pdf->Cell(100,7,iconv("UTF-8", "ISO-8859-1",$ins_venta->limitarCadena($detalle['venta_detalle_descripcion'],80,"...")),'L',0,'C');
		// 	$pdf->Cell(15,7,iconv("UTF-8", "ISO-8859-1",$detalle['venta_detalle_cantidad']),'L',0,'C');
		// 	$pdf->Cell(32,7,iconv("UTF-8", "ISO-8859-1",MONEDA_SIMBOLO.number_format($detalle['venta_detalle_precio_venta'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)),'L',0,'C');
		// 	$pdf->Cell(34,7,iconv("UTF-8", "ISO-8859-1",MONEDA_SIMBOLO.number_format($detalle['venta_detalle_total'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)),'LR',0,'C');
		// 	$pdf->Ln(7);
		// }

		// $pdf->SetFont('Arial','B',9);
		// $pdf->Cell(100,7,iconv("UTF-8", "ISO-8859-1",''),'T',0,'C');
		// 	$pdf->Cell(15,7,iconv("UTF-8", "ISO-8859-1",''),'T',0,'C');

		// $pdf->Cell(32,7,iconv("UTF-8", "ISO-8859-1",'TOTAL A PAGAR'),'T',0,'C');
		// $pdf->Cell(34,7,iconv("UTF-8", "ISO-8859-1",MONEDA_SIMBOLO.number_format($datos_venta['venta_total'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR).' '.MONEDA_NOMBRE),'T',0,'C');

		// $pdf->Ln(7);

		// $pdf->Cell(100,7,iconv("UTF-8", "ISO-8859-1",''),'',0,'C');
		// $pdf->Cell(15,7,iconv("UTF-8", "ISO-8859-1",''),'',0,'C');
		// $pdf->Cell(32,7,iconv("UTF-8", "ISO-8859-1",'TOTAL PAGADO'),'',0,'C');
		// $pdf->Cell(34,7,iconv("UTF-8", "ISO-8859-1",MONEDA_SIMBOLO.number_format($datos_venta['venta_pagado'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR).' '.MONEDA_NOMBRE),'',0,'C');

		// $pdf->Ln(7);

		// $pdf->Cell(100,7,iconv("UTF-8", "ISO-8859-1",''),'',0,'C');
		// $pdf->Cell(15,7,iconv("UTF-8", "ISO-8859-1",''),'',0,'C');
		// $pdf->Cell(32,7,iconv("UTF-8", "ISO-8859-1",'CAMBIO'),'',0,'C');
		// $pdf->Cell(34,7,iconv("UTF-8", "ISO-8859-1",MONEDA_SIMBOLO.number_format($datos_venta['venta_cambio'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR).' '.MONEDA_NOMBRE),'',0,'C');

		// $pdf->Ln(12);

		// $pdf->SetFont('Arial','',9);

		// $pdf->SetTextColor(39,39,51);
		// $pdf->MultiCell(0,9,iconv("UTF-8", "ISO-8859-1","*** Precios de productos incluyen impuestos. Para poder realizar un reclamo o devolución debe de presentar esta factura ***"),0,'C',false);

		// $pdf->Ln(9);

		// $pdf->SetFillColor(39,39,51);
		// $pdf->SetDrawColor(23,83,201);
        // $pdf->Code128(72,$pdf->GetY(),$datos_venta['venta_codigo'],70,20);
        // $pdf->SetXY(12,$pdf->GetY()+21);
        // $pdf->SetFont('Arial','',12);
        // $pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1",$datos_venta['venta_codigo']),0,'C',false);

		$pdf->Output("I","Factura_Nro".''.".pdf",true);

	}else{
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title><?php echo APP_NAME; ?></title>
	<?php include '../views/inc/head.php'; ?>
</head>
<body>
	<div class="main-container">
        <section class="hero-body">
            <div class="hero-body">
                <p class="has-text-centered has-text-white pb-3">
                    <i class="fas fa-rocket fa-5x"></i>
                </p>
                <p class="title has-text-white">¡Ocurrió un error!</p>
                <p class="subtitle has-text-white">No hemos encontrado datos de la venta</p>
            </div>
        </section>
    </div>
	<?php include '../views/inc/script.php'; ?>
</body>
</html>
<?php } ?>