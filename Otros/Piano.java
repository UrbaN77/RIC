import javax.sound.midi.*;//import es pa importar la clase, ahi importa la clase que maneja archivos midi
import java.awt.*;//esto es libreria grafica
import java.awt.Color.*;
import java.awt.event.*;
import javax.swing.*;
import java.util.*;//libreria donde aparece el concepto de Vector(Arreglo dinamico(que crece solito))
import java.io.*;//libreria que maneja archivos
public class Piano extends JFrame{//clase principal
	public static String notas[] ={"do#","re#","fa#","sol#","si#","do","re","mi","fa","sol","la","si"};
public static Vector<teclas> TeclasPiano = new Vector<teclas>();//el arreglo donde iran las teclas..
public Piano(){//Constructor de la clase
	super("Piano");
	Container con = getContentPane();
	con.setBackground(Color.blue);
	setSize(1000,1000);
	setVisible(true);
//addWindowListener(this);
//addKeyListener(this);
addMouseListener(new ElMouseMePelaElCulo(TeclasPiano));//agnadimos que puede usarse el mouse
}
/*public void mouseClicked(MouseEvent pin){
JOptionPane.showMessageDialog(null,"("+pin.getX()+","+pin.getY()+")","piano");
}*/
public void paint(Graphics g){//esta funcion es la que se encarga de pintar los objetos
	super.paint(g);
	int tamy=0;
for(int n =TeclasPiano.size()-1;n>-1;n--){//aca recorremos el Vector que contiene las teclas
if(TeclasPiano.get(n).esSos){//aca preguntamos si es sostenida o no es sostenida
g.setColor(Color.black);//esto define el color del objeto
tamy = 50;
}else{
	g.setColor(Color.white);
	tamy=100;
}
g.fillRect(TeclasPiano.get(n).pos[0],TeclasPiano.get(n).pos[1],60,tamy);//crea rectangulos con color, los parametros son las coordenadas
}
}
public static void main(String args[]){//funcion principal
Piano pipi = new Piano();//instancio el objeto pieano
//Vector<teclas> TeclasPiano = new Vector<teclas>();
for(int n=0;n<notas.length;n++){//recorro el arreglo donde tan los nombres de las notas
	int posTmp[]= new int[2];
	boolean sos=true;
if(notas[n].indexOf("#")!=-1){//aca le pregunto al arreglo si en su cadena tiene el caracter # que significa sostenido(pregunta si la nota es sostenida)
	int t = n;
	if(n>=2)t++;
if(t>n)//Asi hacemos el espacio grande entre las teclas sostenidas
posTmp[0]=260+(t*70);
else
	posTmp[0]=280+(t*70);
posTmp[1]=300;
}else{
	posTmp[0]=260+((n-5)*62);
	posTmp[1]=300;
	sos=!sos;
}
TeclasPiano.add(new teclas(notas[n]+".mid",posTmp,sos));//agnado la tecla que cree al vector
}
for(int n = 0;n<TeclasPiano.size();n++)//recorro el vector para imprimir por consola los resultados
System.out.println(notas[n]+"  "+(TeclasPiano.get(n)).arch+" ("+TeclasPiano.get(n).pos[0]+","+TeclasPiano.get(n).pos[1]+")   "+TeclasPiano.get(n).esSos);
}
}
class ElMouseMePelaElCulo extends MouseAdapter{//clase que permite interaccion con Mouse
Vector<teclas> pepe;//vector que contendra las teclas creadas en la otra clase
public ElMouseMePelaElCulo(Vector<teclas> pepe){//constructor de la clase
this.pepe = pepe;//le asigno las teclas al vector
}
public void Sonar(String a) throws IOException{//funcion que hace posible que suene el piano
try{//esto es para atrapar errores en ejecucion :)
Sequence S=MidiSystem.getSequence(new File(a));//se abre el archivo midi pasado por parametros a un secuenciador(ni puta idea que es eso)
Sequencer seq=MidiSystem.getSequencer();
seq.open();//se abre pa que suene
seq.setSequence(MidiSystem.getSequence(new File(a)));
seq.start();//empieza a sonar
try{
Thread.sleep(1000);//le digo al computador que lo deje sonar 1000 ms osea 1 segundo
}catch(Exception phicar){
	System.err.println(phicar);
}
seq.stop();//paro el midi
}catch(Exception sonido){
	System.err.println(sonido);
}
}
public void mouseClicked(MouseEvent pin){//funcion a la cual el programa va cuando detecta un click
int posX = pin.getX();//posicion en x del click
int posY = pin.getY();//posicion en y
for(int n = 0;n<pepe.size();n++){//recorro vector de teclas
if(posX>=(pepe.get(n)).pos[0] && posX<=((pepe.get(n)).pos[0]+60)){//le pregunto si esta en los parametros x de la tecla
if(posY>=(pepe.get(n)).pos[1] && posY<=((pepe.get(n)).pos[1]+(((pepe.get(n)).esSos)?50:100))){//le pregunto si esta en parametros y
System.out.println((pepe.get(n)).arch);//imprimo el archivo que deberia cargar
try{
Sonar((pepe.get(n)).arch);//cargo el archivo a la funcion Sonar
}catch(IOException p){
	System.err.println(p);
}
return;
}
}
}
//JOptionPane.showMessageDialog(null,"("+pin.getX()+","+pin.getY()+")");
//repaint();
}
}
class teclas{//clase teclas
String arch="pipi.mid";//ruta del archivo
int pos[];//posicion x y y
boolean esSos = false;//si es sharp o no lo es
public teclas(String a,int c[],boolean p){//contructor
	this.arch=a;
	this.pos=c;
	this.esSos=p;
}
}