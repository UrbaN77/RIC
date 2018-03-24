<?php
/******************************************************************************
 * PliggTrin
 * Script para enviar de forma automatizada un enlace a un sitio web hecho con el CMS Pligg.
 * El objetivo es obtener un backlink a una url determinada. Si se requiere se crea un usuario.
 * Se usa un servicio en línea para pasar el recaptcha. 
 * @author: UrbaN77 
 * www.redinfocol.org (2010)
 ******************************************************************************/

// Abrir en consola, no en web.

// Core con funciones necesarias. No se necesita llamar a captcha.php ya que este ya se incluye en el core.
require "core.php";

// URL del sitio Pligg, debe terminar con /
// Parametros: pligg.php http://web.com/pligg/ usuario mipass false null "Hola Mundo" 1 php,programacion "Tutorial de PHP" http://webparabacklink.com/

if (count($argv)<10){
	die("El programa no puede continuar sin los parametros necesarios. Ejemplo: \npligg.php http://web.com/pligg/ usuario mipass false null \"Hola Mundo\" 1 php,programacion \"Tutorial de PHP\" http://webparabacklink.com/");
}

$url = $argv[1];

// Usuario y contraseña

$usuario = $argv[2];
$password = $argv[3];

// Uso de Proxy, para este caso no se está usando

$proxystatus = $argv[4];

// IP:Puerto del proxy
$proxyIp = $argv[5];

// Titulo del Backlink, concatenar con comillas: "Hola Mundo"
$titleBacklink = $argv[6];

// ID de la categoria

$categoryBacklink = $argv[7];

// Tags del Backlink: esto,lotro,aquel
$tagsBacklink = $argv[8];

// Descripción del Backlink, concatenar con comillas: "Interesante herramienta que sirve para esto y lo otro".
$bodytextBacklink = $argv[9];

// Url del Backlink
$urlBacklink = $argv[10];

// Se envia un post con los campos username, password y processlogin. Dichos campos deben ser obtenidos con Tamper Data.
// La documentación de dicha funcion se encuentra en el archivo core.php

$login = curlEnviarPost ( $url."login.php", 
						  $url."login.php", "username=".$usuario."&password=".$password."&processlogin=1", 
						  "true",  $proxyIp, $proxystatus );

// Si el login devuelve ERROR para el script
// Se debe cambiar el string ERROR por el string que devuelva el sitio en especifico.

if (strstr($login, "ERROR")){
	die ("Error iniciando sesion.");
} else {
	// Esta parte es repetitiva y se podría usar la funcion curlEnviarPost para hacer lo mismo, pero por cuestiones de mostrar cómo funciona
	// el CMS y para que sepan que hacer lo pongo de esta forma.
	// Hasta aquí ya debería haber iniciado sesion en el sitio

	$ch = curl_init();
	// Se le indica a cURL que use las cookies que están en el archivo cookies.txt
	curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
	// Useragent como Firefox
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:15.0) Gecko/20100101 Firefox/15.0.1");
	// Limite de conexión
	curl_setopt($ch, CURLOPT_TIMEOUT, 40);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	// En caso de usar proxy
	if ($proxystatus == 'true') {
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);
		curl_setopt($ch, CURLOPT_PROXY, $proxyIp);
	}
	// En caso de que el sitio tenga SSL
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	// Se conecta con la URL que se le indicó pero se especifica que se conecte al archivo submit.php, ese nombre de archivo debe ser reemplazado según el caso.
	// Por esta razón es muy importante terminar lar url con un /. $url = "loquesea.com/";
	curl_setopt($ch, CURLOPT_URL, $url."submit.php");
	// Se hace un referer con el mismo dominio, la web puede no permitir conexiones si no se hace esto.
	curl_setopt($ch, CURLOPT_REFERER, $url."login.php");
	//Se habilita el envio de POST
	curl_setopt($ch, CURLOPT_POST, TRUE);
	// Se envian los parametros url, phase, rankey y key. Todos ellos tomados usando tamper data.
	curl_setopt($ch, CURLOPT_POSTFIELDS, "url=".$urlBacklink."&phase=1&randkey=555&id=c_1");
	// Se guarda el resultado de la conexión en una variable
	$resultado = curl_exec ($ch);
	curl_close ($ch);
	//Verificar Errores
	if(strstr($resultado, "invalid")){
		die("Error: URL Bloqueada o invalida");
	}
	// Como cURL se conecta sin soporte de javascript, reCaptcha lo que hace es motrar el captcha dentro de un iframe.
	// Se toma dicha URL del iframe
	preg_match_all('<input type="hidden" name="id" value="(.*)" />', $resultado, $idLink);
	$id = $idLink[1][0];
	if(strstr($resultado, "recaptcha")){
		preg_match_all('<iframe src="(.*)" height="300" width="500" frameborder="0">', $resultado, $expresion);
		$urlCaptcha = $expresion[1][0];
	// Se envia dicha URL tomada a las funciones del core que se encargan de retornar lo que se necesita.
		$imagenCaptcha = recaptchaObtenerUrlImagen($urlCaptcha);
	// Guarda la imagen del captcha en el servidor para luego ser enviada.
		recaptchaImagen($imagenCaptcha);

	// Como a veces el servicio anti-captcha no está en funcionamiento se hace un ciclo infinito hasta que no devuelva error.
	// Esta funcion hace parte del archivo captcha.php, se le indica el nombre de la imagen que se enviará al servidor y el segundo parametro es
	// la key que da el servicio, dicha key se podría guardar dentro de una variable en el core.php para ser reutilizada.
	// Despues de unos segundos la variable almacena la respuesta del captcha.

		$respuesta = recognize("imagen.jpg","xxxxxxxxxxxxxxxxx",true, "antigate.com");
		$resultadoCaptcha = recaptchaEnviar($respuesta, $urlCaptcha, $imagenCaptcha);

	}else {
		$resultadoCaptcha = "";
	}

	// Se envian los parametros necesarios para que reCaptcha devuelva el código que se enviará a la pagina del marcador, se guarda en una variable para hacer eso.
	// En este caso el pligg envia los parametros como multipart/form-data. Para enviar datos de esa forma se debe crear un array que los contenga
	// Curl automaticamente lo toma como un multipart/form-data.
	
	$postdata = array(
		'title'    => $titleBacklink, 
		'category' => $categoryBacklink, 
		'tags'     => $tagsBacklink,
		'bodytext' => $bodytextBacklink,
		'trackback'	=> '',
		'recaptcha_challenge_field'	=> $resultadoCaptcha,
		'recaptcha_response_field'	=> 'manual_challenge',
		'url'	=> $urlBacklink,
		'phase'	=> '2', 
		'randkey'	=> '555',
		'id'	=> $id,
	);

	// Se vuelve a hacer una conexión con los datos obtenidos en el proceso.
	// Otra vez codigo repetitivo.

	$ch1 = curl_init();
	curl_setopt($ch1, CURLOPT_COOKIEFILE, "cookie.txt");
	if ($proxystatus == 'true') {
		curl_setopt($ch1, CURLOPT_HTTPPROXYTUNNEL, TRUE);
		curl_setopt($ch1, CURLOPT_PROXY, $proxyIp);
	}
	curl_setopt($ch1, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:15.0) Gecko/20100101 Firefox/15.0.1");
	curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch1, CURLOPT_URL, $url."submit.php");
	curl_setopt($ch1, CURLOPT_REFERER, $url."submit.php");
	curl_setopt($ch1, CURLOPT_POST, TRUE);
	curl_setopt($ch1, CURLOPT_POSTFIELDS, $postdata);
	curl_exec ($ch1);

	// Hasta aquí debería haber enviado el enlace.
	// Obtener el link del marcador

	$ch2 = curl_init();
	curl_setopt($ch2, CURLOPT_COOKIEFILE, "cookie.txt");
	curl_setopt($ch2, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:15.0) Gecko/20100101 Firefox/15.0.1");
	curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch2, CURLOPT_URL, $url."user.php?login=".$usuario."&view=history");
	curl_setopt($ch2, CURLOPT_REFERER, $url);
	$resultadoFinal = curl_exec ($ch2);
	if (strstr($resultadoFinal, "story.php?title=")){
		$codigoPagina = explode('<a href="/story.php?title=', $resultadoFinal);
		$urlFinalLink = explode('"',$codigoPagina[1]);
		echo $url."story.php?title=".$urlFinalLink[0];
	}
}
?>