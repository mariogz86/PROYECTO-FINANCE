<?php

	const APP_URL="http://localhost/fixman/";
	const APP_NAME="FIXMAN";
	const APP_SESSION_NAME="POS"; 
	const APP_SESSION=30;

	/******************* Variables para correo ************************** */
	 
	const APP_CharSet = "UTF-8";
	const APP_Host = 'smtp.gmail.com';
	const APP_SMTPAuth = true;
	const APP_Port = 587;
	const APP_Username = 'magbgol@gmail.com';
	const APP_Password = 'snsoslkxdamvmxoe';
	// const APP_SMTPSecure = 'tls';
	 

	const APP_SMTPDebug = 0;	


	/*----------  Configuración de moneda  ----------*/
	const MONEDA_SIMBOLO="C$";
	const MONEDA_NOMBRE="Cordobas";
	const MONEDA_DECIMALES="2";
	const MONEDA_SEPARADOR_MILLAR=",";
	const MONEDA_SEPARADOR_DECIMAL=".";

	const VISTAS=["dashboard","logOut","catalogo","valcatalogo","rol","menu","opcionmenu","opcionrol","usuario","company"];


	/*----------  Marcador de campos obligatorios (Font Awesome) ----------*/
	const CAMPO_OBLIGATORIO='&nbsp; <i class="fas fa-edit"></i> &nbsp;';

	/*----------  Zona horaria  ----------*/
	date_default_timezone_set("America/Managua");

	/*
		Configuración de zona horaria de tu país, para más información visita
		http://php.net/manual/es/function.date-default-timezone-set.php
		http://php.net/manual/es/timezones.php
	*/