<?php
/******************************************************************************
 * Nombre	  : curl_listar_directorios.php
 * Pequeño ejemplo del post https://redinfocol.org/documentacion-curl-un-poderoso-aliado-en-la-seguridad-web/
 * para crear una herramienta con cURL que busca directorios en una URL especifica.
 * @copyLeft  : RIC (www.redinfocol.org) - 2012
 * @author    : UrbaN77
 ******************************************************************************/

// Ejecutar en consola, no en web
if (defined('STDIN')) {
	if ( count($argv) < 2 ) {
		echo "#######################################" .
		"#  TalDirectorio www.RedInfoCol.org   #" .
		"###########################################";
		exit;
	}

	$web = $argv[1];
	$archivo = $argv[2];

	if (file_exists($archivo)) {
		$diccionario = file($archivo);
	} else {
		echo "Archivo no encontrado.";
		exit;
	}

	$fallidos = 0;
	foreach ($diccionario as $recorre){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $web.$recorre);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_exec($ch);
		$estado = curl_getinfo($ch);
		$respuesta=$estado['http_code'];
		echo $web.$recorre. "Estado: ". $respuesta."\n";
		curl_close($ch);
		if ($respuesta == 301 or $respuesta == 200){
			$directorios[]=$web.$recorre;
		}
		if ($respuesta == 404){
			$fallidos++;
		}
	}

	echo "Directorios no encontrados: ". $fallidos."\n";
	echo "Directorios encontrados: ". count($directorios)."\n";

	foreach ($directorios as $encontrados){
		echo $encontrados."\n";
	}

} else {
	echo("Necesitas correr este script desde la consola.");
}