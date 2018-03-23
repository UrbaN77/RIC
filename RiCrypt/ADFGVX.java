/******************************************************************************
 * Nombre : ADFGVX.java
 * Modulo ADFGVX para RiCrypt
 * Cifrado por sustitucion usando coordenadas en una matriz generada por una semilla.
 * @copyLeft  : RIC (www.redinfocol.org) - 2010
 * @package   : RiCrypt
 * @author    : Phicar, phicar@yashira.org
 ******************************************************************************/


public class ADFGVX{
	public static String Tabla[][];
	public static String Seed,TxT,Charset = "abcdefghijklmnopqrstuvxyz",Ep = "adfgx";
	public ADFGVX(String TxT,String Seed){
		this.TxT = DesN(TxT);
		this.Seed = DesN(Seed);
		Tabla = Gen(Seed);
	}
	public static String Cifrar(){
		String Cifrado="";
		for(int n = 0;n<TxT.length();n++)
			Cifrado+=Ep.charAt(PosY(TxT.charAt(n)+"",Tabla))+""+Ep.charAt(PosX(TxT.charAt(n)+"",Tabla));
		return Cifrado;
	}
	public static String DesCifrar(){
		String DesCifrar="";
		for(int n = 0;(n+2)<=TxT.length();n+=2)
			DesCifrar+=Tabla[Ep.indexOf(TxT.charAt(n))][Ep.indexOf(TxT.charAt(n+1))];
		return DesCifrar;
	}
	public static String DesN(String a){
		String b = "";
		a = a.toLowerCase();
		a = a.replaceAll("w","u");
		for(int n =0;n<a.length();n++)
			if(Charset.indexOf(a.charAt(n))!=-1)b+=a.charAt(n);
		return b;
	}
	public static String[][] Gen(String a){
		String tmp="";
		for(int n =0;n<a.length();n++){
			if(tmp.indexOf(a.charAt(n))==-1 && Charset.indexOf(a.charAt(n))!=-1)
				tmp+=a.charAt(n);
		}
		for(int n = 0;n<Charset.length();n++)
			if(tmp.indexOf(Charset.charAt(n))==-1)tmp+=Charset.charAt(n);
//System.out.println(tmp);
		String epa[][]= new String[5][5];
		for(int n = 0;n<tmp.length();n++)
			epa[(int)Math.floor(((double)n)/5.0)][n%5]=String.valueOf(tmp.charAt(n));
		return epa;
	}
	public static int PosY(String a,String b[][]){
		for(int y = 0;y<b.length;y++){
			for(int x = 0;x<b[y].length;x++){
				if(b[y][x].equals(a))
					return y;
			}
		}
		return -1;
	}
	public static int PosX(String a,String b[][]){ 
		for(int y = 0;y<b.length;y++){ 
			for(int x = 0;x<b[y].length;x++){ 
				if(b[y][x].equals(a))
					return x;
			}
		}
		return -1;
	}
}