<?php
//============================================================+
// File name   : example_011.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 011 for TCPDF class
//               Colored Table (very simple table)
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Colored Table
 * @author Nicola Asuni
 * @since 2008-03-04
 * @group table
 * @group color
 * @group pdf
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// extend TCPF with custom functions
class MYPDF extends TCPDF {

	// Load table data from file
	public function LoadData($file) {
		// Read file lines
		$lines = file($file);
		$data = array();
		foreach($lines as $line) {
			$data[] = explode(';', chop($line));
		}
		return $data;
	}

	// Colored table
	public function ColoredTable($header,$data) {
		// Colors, line width and bold font
		$this->setFillColor(255, 0, 0);
		$this->setTextColor(255);
		$this->setDrawColor(128, 0, 0);
		$this->setLineWidth(0.3);
		$this->setFont('', 'B');
		// Header
		$w = array(30, 25, 30,95);
		$num_headers = count($header);
		for($i = 0; $i < $num_headers; ++$i) {
			$this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
		}
		$this->Ln();
		// Color and font restoration
		$this->setFillColor(224, 235, 255);
		$this->setTextColor(0);
		$this->setFont('');

		 //Parametros para las funciones Cell () y MultiCell()
		 $fill = 1;
		 $border='LTR';
		 $ln=0;
		 $fill = 0;
		 $align='T';
		 $link=0;
		 $stretch=0;
		 $ignore_min_height=0;
		 $calign='T';
		 $valign='T';
		 $height=6;//alto de cada columna
		// Data
		$fill = 0;
		foreach($data as $row) {

			$ancho=95;
			$altura=13;
			$cantidad_lineas= strlen($row[0]);
			if($cantidad_lineas > $ancho)
			{
				$cant_espacios = $cantidad_lineas/$ancho;
				$rendondear=round($cant_espacios,1);
				$altura=$altura*$rendondear;
			}//Fin del iff
			//$this->Cell($w[0], 10, $row[0], 'LR', 0, 'L', $fill);
			$this->Cell(30, $altura, $row[1], $border,$ln,$align,$fill,$link,$stretch,$ignore_min_height,$calign,$valign);
			$this->Cell(25, $altura, $row[2],$border,$ln,$align,$fill,$link,$stretch,$ignore_min_height,$calign,$valign);			
			$this->Cell(30, $altura, $row[3],$border,$ln,$align,$fill,$link,$stretch,$ignore_min_height,$calign,$valign);
			$this->MultiCell($ancho, $altura, $row[0],$border,$align,$fill,$ln);
			$this->Ln();
			$fill=!$fill;
		}
		$this->Cell(array_sum($w), 0, '', 'T');
	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('BCN');
$pdf->setTitle('TCPDF Example 011');
$pdf->setSubject('TCPDF Tutorial');
$pdf->setKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 015', PDF_HEADER_STRING);

// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->setHeaderMargin(2);
$pdf->setFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->setFont('helvetica', '', 12);

// add a page
$pdf->AddPage();
//$pdf->Ln(5);
// column titles
$header = array('Country', 'Capital', 'Area (sq km)', 'Pop. (thousands)');

// data loading
$data = $pdf->LoadData('data/table_data_demo.txt');

// print colored table
$pdf->ColoredTable($header, $data);

// ---------------------------------------------------------
 
// close and output PDF document
$pdf->Output('example_011.pdf', 'D');

//============================================================+
// END OF FILE
//============================================================+
