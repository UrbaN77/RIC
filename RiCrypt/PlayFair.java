/******************************************************************************
 * Nombre : PlayFair.java
 * Cifrado por sustitucion usando una matriz 5x5 con el alfabeto y siguiendo reglas.
 * @copyLeft  : RIC (www.redinfocol.org) - 2010
 * @package   : RiCrypt
 * @author    : Phicar, phicar@yashira.org
 ******************************************************************************/

public class PlayFair{
	public static String TxT,Seed;
	public static String Tabla[][] = new String[5][5];
	public static String Charset= "abcdefghijklmnopqrstuvxyz";
	public PlayFair(String TxT,String Seed){
		this.TxT = DesN(TxT);
		this.Seed = DesN(Seed);
		Tabla=Gen(this.Seed);
		for(int n =0;n<5;n++){
			for(int x = 0;x<5;x++)System.out.print(Tabla[n][x]+" ");
				System.out.println("");
		}
	}
	public static String Cifrar(){
		String Cifrado="";
		TxT = (TxT.length()%2==0)?TxT:TxT+"x";
		for(int n = 0;(n+2)<=TxT.length();n+=2){
			String tmp = TxT.substring(n,n+2);
if(tmp.charAt(0)==tmp.charAt(1))//Primera regla
tmp = String.valueOf(tmp.charAt(0))+"x";
int Py = PosY(String.valueOf(tmp.charAt(0)),Tabla);
int Sy = PosY(String.valueOf(tmp.charAt(1)),Tabla);
int Px = PosX(String.valueOf(tmp.charAt(0)),Tabla);
int Sx = PosX(String.valueOf(tmp.charAt(1)),Tabla);
if(Py==Sy)//regla dos
Cifrado+=Tabla[Py][(Px+1)%5]+Tabla[Sy][(Sx+1)%5];
else if(Px==Sx)
	Cifrado+=Tabla[(Py+1)%5][Px]+Tabla[(Sy+1)%5][Sx];
else
	Cifrado+=Tabla[Py][Sx]+Tabla[Sy][Px];
}
return Cifrado;
}
public static String DesCifrar(){
	String DesCifrado="";
	for(int n = 0;(n+2)<=TxT.length();n+=2){
		String tmp = TxT.substring(n,n+2);
if(tmp.charAt(0)==tmp.charAt(1))//Primera regla
tmp = String.valueOf(tmp.charAt(0))+"x";
int Py = PosY(String.valueOf(tmp.charAt(0)),Tabla);
int Sy = PosY(String.valueOf(tmp.charAt(1)),Tabla);
int Px = PosX(String.valueOf(tmp.charAt(0)),Tabla);
int Sx = PosX(String.valueOf(tmp.charAt(1)),Tabla);
if(Py==Sy)//regla dos
DesCifrado+=Tabla[Py][((Px-1)<0)?4:(Px-1)]+Tabla[Sy][((Sx-1)<0)?4:(Sx-1)];
else if(Px==Sx)
	DesCifrado+=Tabla[((Py-1)<0)?4:(Py-1)][Px]+Tabla[((Sy-1)<0)?4:(Sy-1)][Sx];
else
	DesCifrado+=Tabla[Py][Sx]+Tabla[Sy][Px];
}
return DesCifrado;
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
public static String DesN(String a){
	String b = "";
	a = a.toLowerCase();
	a = a.replaceAll("w","u");
	for(int n =0;n<a.length();n++)
		if(Charset.indexOf(a.charAt(n))!=-1)b+=a.charAt(n);
	return b;
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