/*  Solution to Crypto100 by d555.

    Method: BF->you find: http://www.google.com/doodles/thomas-edisons-birthday and the token
    is the date of the publish doodle! ;)
    TOKEN: Feb 11, 2011
     idea: if you look the code, you know what is happening with the 
     messages, so the first will be make algorithm code for decipher on the code i give you.
     Then, you need a keystream for to cipher your message 
     and this key has the same size length of message. The problem is 
     that the keystream is generated for a random choice in your keyboard
     by each part of this. So, we will use the BF on the key ¬¬ (256 posibilities).
     
     Futher, the ciphertext is composited by ascii characters (33-126) 
     and this point you shouldn't forget it.
     There are 95 printable characteres. Look the outputs, and take careful.
*/
#include <iostream>
#include <cstdlib>
#include <string.h>
using namespace std;

int
main ( int argc, char * argv[] )
{
	
	for(int j=0; j<255; j++){
		unsigned int seed=j;
		srand(seed);
		char mensaje[]="g\"O\"&Go4/<l%xwcyt:D;\\T/a50Gorv\"[1JPj]8eNzl/fPMEt'J*b'";
		cout<<mensaje<<endl;
		unsigned short int l_plain=strlen(mensaje);
		char keystream[l_plain];
		int i;
		for(i=0; i<l_plain; i++)
			{	double frand=rand()/double(RAND_MAX);
				unsigned int code=127*(1.-frand)+(frand)*33;
				keystream[i]=(char)code;
			}
			keystream[i]='\0';
			char cipher[l_plain];
			short int num;
			for(i=0; i<l_plain; i++)
			{
				num=( ((int) mensaje[i]) - ( (int) keystream[i]) )  ;
				num=(num<0)? num+127: num+33;
				cipher[i]=(char)(num);	
			}	
			cipher[i]='\0';
			cout<<endl<<cipher<<endl;
		}
		return EXIT_SUCCESS;
	}	