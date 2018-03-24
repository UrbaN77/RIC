<?php
/******************************************************************************
 * PliggTrin - Funciones
 * Funciones necesarias para el funcionamiento del script.
 * @author: UrbaN77 
 * www.redinfocol.org (2010)
 ******************************************************************************/
// Archivo necesario para obtener respuesta del captcha
require "captcha.php";
/* Esta funcion es para hacer más rapido la conexion o inicio de sesion en sitios. Ejemplo:
	curlEnviarPost("miweb.com/login.php","miweb.com/", "usuario=ghost&pass=MelaSudaT0do", false, "null"); */
function curlEnviarPost($url,$ref_url,$data,$login,$proxy,$proxystatus){
/*Se recibe como parametro $login, si es true entonces se crea un archivo llamado cookie.txt (en caso de no existir) para almacenar
 Las sesiones/cookies */
    if($login == 'true') {
        $fp = fopen("cookie.txt", "w");
        fclose($fp);
    }
	// Se inicializa cURL
    $ch = curl_init();
	// Se le indica a cURL en donde tiene que guardar las cookies
    curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
	// Useragent, se envía como un Firefox 15.0.1
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:15.0) Gecko/20100101 Firefox/15.0.1");
	// Tiempo limite de conexión
    curl_setopt($ch, CURLOPT_TIMEOUT, 40);
	// En caso de recibir como true el parametro $proxystatus, se le indica a cURL que permita ese tipo de conexiones y en $proxy se indica la IP:Puerto
    if ($proxystatus == 'true') {
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);
        curl_setopt($ch, CURLOPT_PROXY, $proxy);
    }
	// Para sitios con SSL
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	// Se conecta a la url indicada, que se recibe como parametro en $url
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	// URL de referencia (referer). Algunos sitios sin referer del mismo dominio no permiten la conexion
    curl_setopt($ch, CURLOPT_REFERER, $ref_url);
	// En caso de existir una redirección el script continua a esa dirección
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	// Se le indica a cURL que permita envio de datos por POST
    curl_setopt($ch, CURLOPT_POST, TRUE);
	/*En $data se reciben cada uno de los parametros y sus valores posts a enviar
	  Ejemplo de uso: parametroNombre=Urban&otroParametro=talcosa&otromas=Hola
	Cuando lo que se va a enviar lleva espacios debe enviarse usando urlencode:
	urlencode(parametroNombre=Radical Santos) */
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	// Se ejecuta la conexión y se retorna el contenido.
    return curl_exec ($ch);
	// Se cierra cURl
    curl_close ($ch);
}

/* Esta funcion basicamente guarda la imagen captcha con el nombre de imagen.jpg.
   En caso de hacer multiples ejecuciones del script, habría que cambiar el nombre de la imagen
   por uno que corresponda segun la hora o algun otro valor para no tener problemas al guardar las
   imagenes con el mismo nombre.

   Esta funcion recibe como parametro la URL de la imagen del captcha. Como por ejemplo:
   http://api.recaptcha.net/image?c=03AHJ_Vutz20O14lP049uceFbgFc3vCdnWB6ZYjWqmj4blfutXxxqMZVo6zKJDc-6UmokrVUhY8cJhQ-EFcqaQRnY4Lps9XP7UTcKwe5bosvftYnAkZhLln1y--AZKzcGZ2EKzR5XxgeTrePZBTxx9dz_lNlUlpLlz2XpK_xvfPwXuELJsJAc7_a8*/

function recaptchaImagen($url){
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:15.0) Gecko/20100101 Firefox/15.0.1");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_REFERER, $url);
	$imagen = curl_exec ($ch);
	curl_close ($ch);
	$open = fopen("imagen.jpg", "w");
	fwrite ($open, $imagen);
	fclose ($open);
}

/*		Esta funcion complementa a la anterior lo que hace es acceder a la URL del captcha. Por ejemplo:
		http://api.recaptcha.net/noscript?k=6LfwKQQAAAAAAPFCNozXDIaf8GobTb7LCKQw54EA si prueban accediendo a dicha URL
		se darán cuenta que la imagen siempre va estar cambiando. Haciendo uso de expresiones regulares se toma la dirección de la imagen y
		se envia a la otra funcion para que ella se encargue de descargarla al servidor para luego enviarla al servicio de anti-captcha.*/

function recaptchaObtenerUrlImagen($url){
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:15.0) Gecko/20100101 Firefox/15.0.1");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	$resultado = curl_exec ($ch);
	curl_close ($ch);
	preg_match_all('<img width="300" height="57" alt="" src="(.*)">',$resultado, $expresion);
	return "http://api.recaptcha.net/" . $expresion[1][0];
}

/* Envia la respuesta del captcha, la URL del captcha y la URL de la imagen. Retorna el codigo dado por recaptcha.
	El captcha funciona de la siguiente manera: se necesita la respuesta para el captcha y ademas de eso se necesita un código unico
	para dicha imagen, y ademas de eso se envian los datos a la URL del captcha:
	$respuesta = respuesta entregada por el servicio anticaptcha.
	$urlCaptcha = Es una url del tipo http://api.recaptcha.net/noscript?k=*TOKEN* a esa dirección es que se envía la respuesta.
	$urlImagen = Se envia la url de la imagen, la que se había obtenido con la función recaptchaObtenerUrlImagen. Su funcionamiento es
	http://api.recaptcha.net/image?c=*TOKEN* tomar el token de la imagen, que será con la que reCaptcha compara si de verdad es la respuesta correcta para
	esa imagen. En caso de salir todo bien devuelve un código que se envía al sitio del marcador.
		*/
	
function recaptchaEnviar($respuesta, $urlCaptcha, $urlImagen){
	$expresion = explode("http://api.recaptcha.net/image?c=", $urlImagen);
	$codigoCaptcha = $expresion[1];
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:15.0) Gecko/20100101 Firefox/15.0.1");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $urlCaptcha);
	curl_setopt($ch, CURLOPT_REFERER, $urlCaptcha);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "recaptcha_challenge_field=".$codigoCaptcha."&recaptcha_response_field=".urlencode($respuesta)."&submit=Soy+humano");
	$resultado = curl_exec ($ch);
	if (!strstr($resultado, "recaptcha_response_field")){
	$codigoPagina = explode('<textarea rows="5" cols="100">', $resultado);
	return $codigoRecaptcha = substr($codigoPagina[1], 0, -25);
	}else{
		die ("Error en la respuesta");
	}
}
?>