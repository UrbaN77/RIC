  /************************************************
*  Nombre : Vigenere.cpp                        *
*                                               *
*  Cifrado de cesar de sustitución multiple     *
*  por medio de sumas y restas del alfabeto     *
*                                               *
*  Fulapol                                      *
*  Fulapol@gmail.com                            *
*  my.opera.com/fulapol                         *
*                                               *
************************************************/

#include <string>

Public Class Vigenere{
private:
	string texto,semilla,letras;
	letras = "abcdefghijklmnopqrstuvwxyz"
	string limpiar(string cadena);
//Las variables son privadas, el metodo limpiar
//es privada ya que solo lo utilizan los otros
//metodos para limpiar la entrada

public:
	vigenere(string orig, string sem);
	string cifrar();
	string descifrar();
//Esto es publico porque es la unica forma de
//accesar a los datos de la clase

}

/************************************************
*                                               *
*  El Constructor que debe recibir la cadena a  *
*  tratar y la cadena semilla                   *
*                                               *
************************************************/

vigenere::vigenere(string orig, string sem){
	this->texto = limpiar(orig);
	this->semilla = limpiar(sem);
}

/************************************************
*                                               *
*  El metodo cifrar, que regresa una cadena de  *
*  texto que solo despues de ser cifrada        *
*                                               *
************************************************/

vigenere::cifrar(){
	string final;
	int i,movimiento,rotacion,total;
	size_t pos,pos2;
//Las variables serviran para registrar la posicion
//de las letras y calcular la semilla

	for(i=0,rotacion=0;i<texto.length();i++,rotacion++){
		pos = letras.find(texto.at(i));
		pos2 = letras.find(semilla.at(rotacion));
		total = ((int)(pos+pos2))%letras.length();
//El total que marca la posicion de la letra y la
//semilla sumadas

		if(total > letras.legth())
			movimiento = total-letras.length();
		else
			movimiento = total;
//Para asegurarnos que esta dentro del rango

		final.apend(letras,movimiento,1)
		if(rotacion==semilla.length())
			rotacion=0;
//Si se acaba la semilla, regresar al principio

	}
	return final;
}

/************************************************
*                                               *
*  El metodo descifrar, que regresa una cadena  *
*  de texto que solo despues de ser descifrada  *
*                                               *
************************************************/

vigenere::descifrar(){
//La funcion es exactamente la misma que en el metodo
//cifrar, solo que aqui las posiciones se restan para
//eliminar la semilla del texto

	string final;
	int i,movimiento,rotacion,total;
	size_t pos,pos2;
	for(i=0,rotacion=0;i<texto.length();i++,rotacion++){
		pos = letras.find(texto.at(i));
		pos2 = letras.find(semilla.at(rotacion));
		total = ((int)(pos-pos2))%letras.length();
		if(total < 0)
			movimiento = total+letras.length();
		else
			movimiento = total;
		final.apend(letras,movimiento,1)
		if(rotacion==semilla.length())
			rotacion=0;
	}
	return final;
}

/************************************************
*                                               *
*  El metodo limpiar, que regresa una cadena de *
*  texto que solo contiene los caracteres       *
*  soportados por la clase                      *
*                                               *
************************************************/

vigenere::limpiar(string cadena){
	int i;
	string final;
	for(i=0;i<cadena.length();i++){
		if(cadena.at(i)>64 && cadena.at(i)<91)
			cadena.at(i) += 32;
//Pasamos el caracter a minusculas

		if(letras.find(cadena.at(i))!=-1)
			final.apend(cadena.at(i))
//Agregamos a la cadena final el caracter
//si se encuentra en la variable letras

	}
	return final;
}