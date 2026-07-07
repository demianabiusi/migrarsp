<?php
@session_start();
mysqli_report(MYSQLI_REPORT_OFF);




	$srv=simplexml_load_file('servidores.xml');
	$listasrv=array();
	$arraysrv=array();
	
	
	foreach($srv->servidor as $servidor)
	{
			$listasrv[count($listasrv)]=$servidor->nombre;
			$arraysrv[count($arraysrv)]=$servidor;
	}
	

	function Conectar_Con($sx)
	{	
			$mylink = mysqli_connect
						($sx->host, 
						 $sx->usuario, 
						 $sx->clave, 
						 $sx->base,
						 (int) $sx->puerto) 
			or die('No puedo conectar con ').$sx->nombre;
			
			return $mylink;
	}
	
	


?>
