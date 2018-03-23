// Reto navideño: https://redinfocol.org/writeup-reto-navideno/
//palabras dichoso,cavilaba,penso
import java.util.*;
import java.io.*;
import java.net.*;
public class grinch{
	public static void main(String args[]) throws IOException{
		while(true){
			Socket s = new Socket("204.236.196.151",9999);
			InputStream in = s.getInputStream();
			BufferedReader lector = new BufferedReader(new InputStreamReader(System.in));
			OutputStream out = s.getOutputStream();
			byte t[] = 
			{(byte)0x2D,(byte)0xC2,(byte)0xBF,(byte)0x53,(byte)0x72,(byte)0x2E,(byte)0x20,(byte)0x47,(byte)0x72,(byte)0x69,(byte)0x6E,(byte)0x63,(byte)0x68,(byte)0x3F,(byte)0x20,(byte)0x2E,(byte)0x2E,(byte)0x2E,(byte)0x20,(byte)0xC2,(byte)0xBF,(byte)0x53,(byte)0x72,(byte)0x2E,(byte)0x20,(byte)0x47,(byte)0x72,(byte)0x69,(byte)0x6E,(byte)0x63,(byte)0x68,(byte)0x3F};
			out.write(t);
			try{
				boolean muaks = true,mu = true;
				do{
					do{
//out.write(t);
						if(!muaks)mu = false;
						byte entra[] = new byte[1000];
						int len = in.read(entra);
						System.out.println(new String(entra,0,len,"UTF-8"));
						if(new String(entra,0,len,"UTF-8").equals("?:"))muaks = false;
						t = new byte[len];
						for(int n = 0;n<len;n++)
							t[n]=entra[n];

					}while(muaks);
					out.write((lector.readLine()).getBytes("UTF-8"));
				}while(true);
			}catch(Exception muak){}
		}
	}
}
