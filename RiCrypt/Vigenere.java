/******************************************************************************
 * Nombre : Vigenere.php
 * Clase para el cifrado de Vigenere para RIC (redinfocol.org). Este es un cifrado de
 * sustitución polialfabetico, siendo una variación del cifrado de cesar. La diferencia
 * es que usa una palabra como semilla, la cual cada caracter representa el indice
 * del charset a ser sumado en las operaciones modulares. Esta semilla se repita cuantas
 * veces sea necesario hasta cifrar/descifrar toda la cadena.
 * @copyLeft  : RIC (www.redinfocol.org) - 2009
 * @package   : RiCrypt
 * @author    : Phicar, phicar@yashira.org
 ******************************************************************************/
public class Vigenere {

	public static String TxT,Seed;
	public static String Charset = "abcdefghijklmnopqrstuvwxyz";
	
	public Vigenere(String TxT,String Seed) {
		this.Seed = Desn(Seed);
		this.TxT = TxT;
	}

public static String Cifrar() {

	String Cifrado = "",a=TxT.toLowerCase();
	for(int n = 0,c=0;n<TxT.length();n++,c = (c+1)%Seed.length()){
		if(Charset.indexOf(a.charAt(n))!=-1){
			int tmp = (Charset.indexOf(a.charAt(n))+Charset.indexOf(Seed.charAt(c)))%Charset.length();
			Cifrado+=(Charset.indexOf(TxT.charAt(n))!=-1)?Charset.charAt(tmp):String.valueOf(Charset.charAt(tmp)).toUpperCase();
		}
		else {
			c-=1;
			Cifrado+=TxT.charAt(n);
		}
	}

	return Cifrado;

}
public static String DesCifrar() {

	String DesCifrado = "";
	String a = TxT.toLowerCase();
	for(int n = 0,c=0;n<a.length();n++,c=(c+1)%Seed.length()){
		if(Charset.indexOf(a.charAt(n))!=-1){
			int tmp = (Charset.indexOf(a.charAt(n))-Charset.indexOf(Seed.charAt(c)));
			tmp = (tmp<0)?(tmp+Charset.length()):tmp;
			DesCifrado+=(Charset.indexOf(TxT.charAt(n))!=-1)?Charset.charAt(tmp):String.valueOf(Charset.charAt(tmp)).toUpperCase();
		}
		else {
			c-=1;
			DesCifrado+=TxT.charAt(n);
		}
	}

	return DesCifrado;

}

public static String Desn(String a) {
	String b = "";
	for(int n = 0;n<a.length();n++){
		if((Charset.indexOf(a.charAt(n))!=-1)||(Charset.indexOf(String.valueOf(a.charAt(n)).toLowerCase())!=-1))
			b+=a.charAt(n);
	}

	return b;

}
}