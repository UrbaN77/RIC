<?php
/******************************************************************************
 * Nombre : Vigenere.php
 * Clase para el cifrado de Vigenere para RIC (redinfocol.org). Este es un cifrado de
 * sustituci�n polialfabetico, siendo una variaci�n del cifrado de cesar. La diferencia
 * es que usa una palabra como semilla, la cual cada caracter representa el indice
 * del charset a ser sumado en las operaciones modulares. Esta semilla se repita cuantas
 * veces sea necesario hasta cifrar/descifrar toda la cadena.
 * @copyLeft  : RIC (www.redinfocol.org) - 2012
 * @package   : RiCrypt
 * @author    : D-m-K, d4rk.m0nk3y@gmail.com
 ******************************************************************************/
class Vigenere {
    var $charset;
	var $txt;
	var $seed;
 
	/*****************************************************************************
	* Constructor
	* Cifra la cadena pasada como parametro realizando la sustitucion
	* de la cadena original la cantidad de veces definida en el parametro
	* @param  $s = Cadena original
	*                $seed = Cantidad de rotaciones a cada letra
	******************************************************************************/
    public function __construct($s, $seed) {
        $this->charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";	//Definicion del charset
		$this->txt    = strtoupper($s);
		$this->seed   = strtoupper($seed);
    }
 
	/************************************************************************************
	* Funcion :  encode
	* Cifra la cadena pasada como parametro realizando una sustitucion
	* polialfabetica a traves de sumas modulares dependiendo el indice de cada caracter de la semilla
	* @param  $seed = Semilla con la que sera cifrado el mensaje	
	* @return $result = Mensaje cifrado
	************************************************************************************/
    function encode(){
		$result = "";						//Cadena donde queda el resultado
		$x = 0;								//Indice de la semilla
		$p = 0;								//Posicion para relizar la operacion modular
		for($i=0; $i<strlen($this->txt); $i++){
			if(strstr($this->charset, $this->txt{$i})){//Evaluo si existe el caracter en el charset
				$x = strpos($this->charset, $this->seed{($p % strlen($this->seed))});
				$result .= $this->rotate($this->txt{$i}, $x);	//Invoco funcion que hace la rotacion
				$p++; //Aumento el indice para la operacion modular
			}else{//Si no existe dejo el caracter evaluado
				$result .= $this->txt{$i};
				continue;
			}	
		}			
        return $result;						//Devuelvo la cadena Cifrada =)
    }
 
 	/************************************************************************************
	* Funcion  :  decode
	* DesCifra la cadena pasada como parametro realizando una sustitucion
	* polialfabetica a traves de sumas modulares dependiendo el indice de cada caracter de la semilla
	* @param  $seed = Semilla con la que sera cifrado el mensaje	
	* @return $result = Mensaje cifrado
	************************************************************************************/
    function decode(){
		$result = "";						//Cadena donde queda el resultado
		$x = 0;								//Indice de la semilla
		$p = 0;								//Posicion para relizar la operacion modular
		for($i=0; $i<strlen($this->txt); $i++){
			if(strstr($this->charset, $this->txt{$i})){//Evaluo si existe el caracter en el charset
				$x = strpos($this->charset, $this->seed{($p % strlen($this->seed))});
				$result .= $this->rotate($this->txt{$i}, -$x);	//Invoco funcion que hace la rotacion
				$p++; //Aumento el indice para la operacion modular
			}else{//Si no existe dejo el caracter evaluado
				$result .= $this->txt{$i};
				continue;
			}	
		}			
        return $result;						//Devuelvo la cadena DesCifrada =)
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
        $result = "";			//Texto de salida
        $tamC = strlen($this->charset); //Longitud de la cadena del charset
		$k = 0; 				//Indice para sustitucion de la cadena con el charset
		$n %= $tamC;			//Semilla � rotacion
		$c = strtoupper($c);	//Convierto a mayuscula el caracter		
		//Realizo la sustituci�n de cada caracter        
        //Evaluo si el caracter en la posicion $i existe, de lo contrario
        //Dejo el caracter que esta por defecto
		if(strstr($this->charset, $c)){
			$k = (strpos($this->charset, $c) + $n);
			if($k < 0){
				$k += $tamC;
			}else
				$k %= $tamC;
			$result .= $this->charset{$k};
		}else{
			$result .= $c;
		}
		return $result;
	}
}
?> 