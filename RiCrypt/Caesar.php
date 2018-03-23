<?php
/*****************************************************************************
 * Nombre : Caesar.php
 * Implementacion del cifrado de cesar para RIC (www.redinfocol.org). Este cifrado usa 
 * una clave de sustituci�n simple. En la actualidad existen muchos codes similares a este y el mas 
 * conocido se llama Rot13, el cual realiza una rotacion de 13 caracteres la cadena original. 
 * @copyLeft  : RIC - 2012
 * @package   : RiCrypt
 * @author      : D-m-K, d4rk.m0nk3y@gmail.com
/******************************************************************************/
class Caesar {
    var $charset;
	var $txt;
	var $rot;
 
	/*****************************************************************************
	* Constructor
	* Cifra la cadena pasada como parametro realizando la sustitucion
	* de la cadena original la cantidad de veces definida en el parametro
	* @param  $s = Cadena original
	*                $n = Cantidad de rotaciones a cada letra
	******************************************************************************/
    public function __construct($s, $n) {
        $this->charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";	//Definicion del charset
		$this->txt    = $s;
		$this->rot    = $n;
    }
 
	/*****************************************************************************
	* Funcion : encode
	* Cifra la cadena pasada como parametro realizando la sustitucion
	* de la cadena original la cantidad de veces definida en el parametro
	* @return $result = Mensaje cifrado
	*****************************************************************************/
    function encode(){
		$result = "";								//Variable donde queda el resultado
        for($i=0; $i<strlen($this->txt); $i++)  	//Realizo la sustituci�n de cada caracter
            $result .= $this->rotate($this->txt{$i}, $this->rot);	//Invoco funcion que hace la rotacion
        return $result;								//Devuelvo la cadena Cifrada =)
    }
 
 	/*****************************************************************************
	* Funcion : decode
	* Cifra la cadena pasada como parametro realizando la sustitucion
	* de la cadena original la cantidad de veces definida en el parametro
	* @return $result = Mensaje Descifrado
	*****************************************************************************/
    function decode(){
		$result = "";								//Variable donde queda el resultado
        for($i=0; $i<strlen($this->txt); $i++)  	//Realizo la sustituci�n de cada caracter
            $result .= $this->rotate($this->txt{$i}, -$this->rot);	//Invoco funcion que hace la rotacion
        return $result;								//Devuelvo la cadena DesCifrada =)
    }
 
	/*****************************************************************************
	* Funcion : rotate
	* Realiza la rotacion de un caracter sobre el charset dependiendo el valor de la semilla
	* de la cadena original la cantidad de veces definida en el parametro
	* @param  $s = Cadena original
	*                 $n = Cantidad de rotaciones
	* @return  $result = Valor del nuevo caracter de acuerdo al indice
	*****************************************************************************/
	function rotate($c, $n){
        $result = "";					//Texto de salida
        $tamC = strlen($this->charset); //Longitud de la cadena del charset
		$k = 0; 						//Indice para sustitucion de la cadena con el charset
		$n %= $tamC;					//Semilla � rotacion
		$c = strtoupper($c);			//Convierto a mayuscula el caracter
        //Evaluo si el caracter en la posicion $i existe, de lo contrario dejo el caracter que esta por defecto
		if(strstr($this->charset, $c)){
			$k = (strpos($this->charset, $c) + $n);
			if($k < 0){					//Evaluo que el indice sea mayor a 0
				$k += $tamC;
			}else
				$k %= $tamC;
			$result .= $this->charset{$k};	//Obtengo del charset el indice de $k, que es el nuevo valor
		}else{
			$result .= $c;				//Dejo el caracter sin modificaciones
		}
		return $result;					//Devuelvo el caracter con el nuevo valor
	}
}
?> 