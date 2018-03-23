/******************************************************************************
 * Nombre : RSAProb.java
 * RSA es un algoritmo que usa la complejidad del algoritmo de factorización de números compuestos 
 * en sus factores primos, es además un cifrado de llave pública y privada, o sea el arrogante se cifra 
 * de una forma y de otra se (des)cifra.  Se tienen dos primos potencialmente grandes llamados p y q, 
 * la multiplicación de ellos se llamará n (n=pq), se usará además la cantidad de coprimos de n y menores 
 * a sí (phi(n)) acá como sabemos cuáles son los dos factores de n entonces para que lo descomponemos.
 * @copyLeft  : RIC (www.redinfocol.org) - 2011
 * @package   : RiCrypt
 * @author    : Phicar, phicar@yashira.org, http://www.redinfocol.org/cifrados-rsa/
 ******************************************************************************/

import java.util.*;
import java.math.BigInteger;

public class RSAProb{
	
	public static String error = "Usage: java RSAProb [option] [data] [(plain/cipher) text]\nOptions:  -c to cipher\n          
	dn-- to (des)cipher\nData: \nfor cipher, p and q as primes\nfor (des)cipher d n
	\nphicar@yashira.org->redinfocol.org";

	public static void main( String args[] ){

		System.out.println(ToHex( new BigInteger(args[1]),8 ));

		if( args[0].equalsIgnoreCase("-c") ){
			try{
				BigInteger p = new BigInteger(args[1]);
				BigInteger q = new BigInteger(args[2]);
				if( !p.isProbablePrime(100) || !q.isProbablePrime(100) )
					return;
				BigInteger n = p.multiply(q);
				BigInteger Tn = (p.subtract(BigInteger.ONE)).multiply(q.subtract(BigInteger.ONE));
				BigInteger e=new BigInteger("2");
				//BUSCAMOS E COMO K | GCD(K,TOT(n))==1
				while(GcD(e,Tn).compareTo(BigInteger.ONE)!=0) e=e.add(BigInteger.ONE);
				BigInteger d = Tn.subtract(BigInteger.ONE);
				//BUSCAMOS D COMO K | 1while(((e.multiply(d)).mod(Tn)).compareTo(BigInteger.ONE)!=0)
				d=d.subtract(BigInteger.ONE);
				int n2 = 1;
				for(;!((((new BigInteger("2").pow(n2-1)).subtract(BigInteger.ONE)).compareTo(n)0));n2++);
					System.out.println("n="+n+",Tn(n)="+Tn+",e="+e+",d="+d+",n2="+n2);
				String plain = args[3];
				String tmp ="";
				//PASAMOS A BINARIO EL PLAIN
				for(int x = 0;xtmp+=ToBin((int)plain.charAt(x),7);
				//HACEMOS PADDING POR LA DERECHA MIENTRAS SEA N2-1
					while(tmp.length()%(n2-1)!=0)tmp+="0";
//System.out.println(tmp);
//HACEMOS EL MAXIMO DE CARACTERES BASE-16
/*int u = 0;
while(())u++;
no se si hacer con hexadecimal o con binario, hexadecimal esta muy jodido no se porque
*/
String tmp2 = "";
//GENERAMOS LOS NUMEROS y por ende el final en HEXADECIMAl, uso de longitud en hexadecimal n2 que es longitud en bytes/4 ya que 2^4=16
for(int y=0;(y+(n2-1))}
	System.out.println(tmp2);
}catch(Exception Phicar){
	System.err.println("Hptaaaaaaa Error, la cagaste en algo o la cague yo, lo importante es que me postees en que fue xDDD\n"+Phicar+
		"\n recuerda usar bien el programa de la siguiente manera:\n"+error);
}
}

else if( args[0].equalsIgnoreCase("-d") ){
	try{
		BigInteger d = new BigInteger(args[1]);
		BigInteger n = new BigInteger(args[2]);
		int n2 = 1;
		for(;!((((new BigInteger("2").pow(n2-1)).subtract(BigInteger.ONE)).compareTo(n)0));n2++);
			System.out.println("n2="+n2);
		String tmp = "";
//de hex a los numeros ;)
		for(int x = 0;(x+((n2/4)+4))}
			tmp = tmp.substring(0,tmp.length()-1);
//separo numeros, no encontre mejor metodo, espero sepan obviar mis chambonadas de novato ;) jejejeje
			StringTokenizer token = new StringTokenizer(tmp,",");
			String tmp2="";
			while(token.hasMoreTokens())
				tmp2+=ToBin(Integer.parseInt(token.nextToken()),n2-1);
//System.out.println(tmp2);
			String res = "";
			for(int x = 0;(x+7)0);
				while(tmp.length()-1;n--,c++)
			tmp+=charset.indexOf(a.charAt(n))*Math.pow(b,c);
			return tmp;
		}
		public static String ToBin(int a,int b){
			String tmp = "";
			do{
				tmp = (a%2)+tmp;
			}while((a>>=1)>0);
			while(tmp.length()return tmp;
		}
		public static BigInteger GcD(BigInteger a,BigInteger b){
			if(b.compareTo(new BigInteger("0"))==0)
				return a;
			return GcD(b,a.mod(b));
		}
	}