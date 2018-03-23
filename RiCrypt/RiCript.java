/******************************************************************************
 * Nombre : RiCript.java
 * Un cifrador de archivos, con una clave de x longitud...
 * Se usa un cifrado de flujo con el operador que tiene la reversa fija con
 * el conocidicimo XOR, lo que hago es un or exclusivo y escondo formato, muyyy breve pero util.
 * @copyLeft  : RIC (www.redinfocol.org) - 2010
 * @package   : RiCrypt
 * @author    : Phicar, phicar@yashira.org
 ******************************************************************************/
import java.io.*;
import java.util.*;
public class RiCript{
	public static void main(String args[]) throws IOException{
		if(args.length != 3){
			System.out.println("Usage:java RiCript [option] [file] [password]\n\n[option]--> -c For Encrypt the file.\n--> -d For Decrypt the file.\n[file] is the file to encrypt or decrypt.\n[password] is the salt used for encrypt the file.");
			return;
		}else{
			if(!(new File(args[1]).exists())){
				System.out.println("I can not find that file :S\n\nUsage:java RiCript [option] [file] [password]\n\n[option]--> -c For Encrypt the file.\n--> -d For Decrypt the file.\n[file] is the file to encrypt or decrypt.\n[password] is the salt used for encrypt the file.");
				return;
			}
			FileInputStream flin = new FileInputStream(args[1]);
			byte archivo[] = new byte[(int)(new File(args[1]).length())];
			flin.read(archivo);
			if(args[0].equalsIgnoreCase("-c")){
				byte salida[]=new byte[archivo.length+1+(args[1].substring(args[1].indexOf(".")+1,args[1].length())).length()];
				int c = 0;
				if(args[1].indexOf(".")==-1){
					System.out.println("I do not cypher files without extension :P.\n\nUsage:java RiCript [option] [file] [password]\n\n[option]--> -c For Encrypt the file.\n--> -d For Decrypt the file.\n[file] is the file to encrypt or decrypt.\n[password] is the salt used for encrypt the file.");
					return;
				}
				for(int n = args[1].indexOf(".")+1;n<args[1].length();n++,c++)
					salida[c]=(byte)args[1].charAt(n);
				salida[++c]=(byte)0;
				for(int p=0,x=0;p<archivo.length;p++,c++,x=((x+1)%args[2].length())){
					byte val1 = archivo[p];
					byte val2 = (byte)args[2].charAt(x);
					salida[c]=(byte)(val1^val2);
				}
				FileOutputStream flout = new FileOutputStream(args[1].substring(0,args[1].indexOf(".")));
				flout.write(salida);
			}else if(args[0].equalsIgnoreCase("-d")){
				String extension = "";
				for(int n = 0;archivo[n]!=0;n++)
					extension+=(char)((int)archivo[n]);
				byte salida[] = new byte[archivo.length-(extension.length()+1)];
				for(int n = 0,x=0,y=(extension.length()+1);n<salida.length;n++,y++,x=(x+1)%args[2].length())
					salida[n]=(byte)(archivo[y]^((byte)args[2].charAt(x)));
				FileOutputStream flout = new FileOutputStream(args[1]+"."+extension);
				flout.write(salida);
			}else{
				System.out.println("I do not recognize that option :P\n\nUsage:java RiCript [option] [file] [password]\n\n[option]--> -c For Encrypt the file.\n--> -d For Decrypt the file.\n[file] is the file to encrypt or decrypt.\n[password] is the salt used for encrypt the file.");
				return;
			}
		}
	}
}