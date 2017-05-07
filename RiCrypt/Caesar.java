/*****************************************************************************
 * Nombre : Caesar.java
 * Implementación del cifrado de cesar para RIC (www.redinfocol.org). Este cifrado usa 
 * una clave de sustitución simple. En la actualidad existen muchos codes similares a este y el mas 
 * conocido se llama Rot13, el cual realiza una rotacion de 13 caracteres la cadena original. 
 * @copyLeft  : RIC - 2010
 * @package   : RiCrypt
 * @author    : Phicar, phicar@yashira.org
/******************************************************************************/
// package RiCrypt;

public class Caesar {

	public static String CharsetMin = "abcdefghijklmnopqrstuvwxyz";
	public static String CharsetMay = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	public static String TxT;
	public static int Pos;
	/*
	Constructor
	@params Txt as String sera el txt a cifrar o a descifrar.
	Pos as Integer sera el numero de posiciones a cifrar.
	*/
	public Caesar(String TxT,int Pos){
	this.TxT=TxT;
	this.Pos=Pos;

}

/*
Funcion Cifrar
Cifra la cadena de texto introducida en el constructor
bajo el algoritmo Caesar usando una sumatoria modular
de la siguiente manera
(x+y)%p
donde x sera el indexo en el alfabeto de la letra en
cuestion, y sera la posicion o semilla introducida
en el algoritmo y p sera la longitud del alfabeto.
*/

public static String Cifrar(){

	String Cifrado = "";

	for( int n = 0;n<TxT.length();n++ ){

		if( (CharsetMin.indexOf(TxT.charAt(n))!=-1) || (CharsetMay.indexOf(TxT.charAt(n))!=-1) )
			Cifrado+=(CharsetMin.indexOf(TxT.charAt(n))!=-1)?CharsetMin.charAt(((CharsetMin.indexOf(TxT.charAt(n)))+Pos)%CharsetMin.length()):CharsetMay.charAt((CharsetMay.indexOf(TxT.charAt(n))+Pos)%CharsetMay.length());
		else
			Cifrado+=TxT.charAt(n);

	}

	return Cifrado;
}

/*
Funcion DesCifrar
DesCifra el texto cifrado introducido en el constructor
bajo el algoritmo Caesar usando una resta modular
de la siguiente manera
(x-y)%p
donde x sera el indexo en el alfabeto de la letra en
cuestion, y sera la posicion o semilla introducida
en el algoritmo y p sera la longitud del alfabeto.
*/

public static String DesCifrar(){

	String DesCifrado = "";

	for( int n = 0;n<TxT.length();n++ ){
		if( ( CharsetMin.indexOf(TxT.charAt(n))!=-1 ) || ( CharsetMay.indexOf(TxT.charAt(n))!=-1 ) )
			DesCifrado+=(CharsetMin.indexOf(TxT.charAt(n))!=-1)?CharsetMin.charAt((CharsetMin.indexOf(TxT.charAt(n))-Pos)%CharsetMin.length()):CharsetMay.charAt((CharsetMay.indexOf(TxT.charAt(n))-Pos)%CharsetMay.length());
		else
			DesCifrado+=TxT.charAt(n);
	}

	return DesCifrado;

	}
}