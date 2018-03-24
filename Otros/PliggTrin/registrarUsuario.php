<?php
/******************************************************************************
 * PliggTrin - Registro de usuarios
 * Script parte de PliggTrin para registrar usuarios en webs con el CMS Pligg.
 * @author: UrbaN77 
 * www.redinfocol.org (2010)
 ******************************************************************************/

// Abrir en consola, no en web.
// Core con funciones necesarias. No se necesita llamar a captcha.php ya que este ya se incluye en el core.
// parametros: php registrarUsuario.php http://mipligg.com/ usuario email@dominio.com password false null

require "core.php";
$url = $argv[1];
$usuario = $argv[2];
$email = $argv[3];
$password = $argv[4];
$proxystatus = $argv[5];
$proxyIp = $argv[6];
$ch = curl_init();
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:15.0) Gecko/20100101 Firefox/15.0.1");
curl_setopt($ch, CURLOPT_TIMEOUT, 40);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
if ($proxystatus == 'true') {
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);
        curl_setopt($ch, CURLOPT_PROXY, $proxyIp);
}
curl_setopt($ch, CURLOPT_URL, $url."register.php");
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_REFERER, $url);
$resultado = curl_exec ($ch);
curl_close ($ch);
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
	$respuesta = recognize("imagen.jpg","xxxxxxxxxxxxx",true, "antigate.com");
	$resultadoCaptcha = recaptchaEnviar($respuesta, $urlCaptcha, $imagenCaptcha);
	}else {
	$resultadoCaptcha = "";
	}
$registro = curlEnviarPost( $url."register.php", 
						    $url."login.php", 
						    "reg_username=".$usuario."&reg_email=".$email.
							"&reg_password=".$password."&reg_password2=".$password."&recaptcha_challenge_field=".$resultadoCaptcha.
							"&recaptcha_response_field=".$respuesta."&submit=Create+user&regfrom=full", 
							"false",  
							$proxyIp, 
							$proxystatus);

echo "Usuario: ".$usuario." con contraseña: ".$password." creado con exito :)";
/*
Entrar al correo de Gmail para verificar el usuario
function check_email($username, $password)
{ 
    //url to connect to
    $url = "https://mail.google.com/mail/feed/atom"; 
    // sendRequest 
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_ENCODING, "");
    $curlData = curl_exec($curl);
    curl_close($curl);

    //returning retrieved feed
    return $curlData;
}
$em = "talescosas@gmail.com";
$pw = "";
$feed = check_email($em, $pw);
$x = new SimpleXmlElement($feed);
echo($x->entry->link->attributes()->href);*/
?>
