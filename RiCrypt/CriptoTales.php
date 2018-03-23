<?php
/******************************************************************************
Probablemente ni Phicar entienda hoy en día qué escribió aquí.
CriptoTales: La idea era crear un cifrador de mensajes en el muro de Facebook.
******************************************************************************/

$charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
$ps;//El stream de RC4
$aR = 0;
$bR = 0;
echo DesRC4(RC4("Hola Amiguitos como estan","Diego"),"Diego");
echo '<br>'.Cifr("34636","133452"," 523643674375 fsdgdshjsjsr setjrwkjwsrjth gewjwtrj");
echo '<br>'.DesCifr("34636","133452",Cifr("34636","133452","HaghajetkjajargWGHQB3 Q35YAE5JAHWQNQ "));
$joderT[] = "232564";
$joderT[] = "25474848689679";
$joderT[] = "25474848689678";
echo '<br>'.CifrEst("1234235",$joderT,"FAFGGWGQRYEYH WEGWGTbalanahahhfdhdf  gwgwgg gsdgsdg");
echo '<br>'.DesCifrEst("1234235","25474848689678",CifrEst("1234235",$joderT,"hjahadhadh	 balanahahhfdhdf  gwgwgg gsdgsdgo"));
echo '<br>'.ModCer("5898446465371178700","231540");
function CharsetMod($a){
	global $charset; 
	$a%=64;
	return substr($charset,$a,strlen($charset)).substr($charset,0,$a);
}
function Base64($a,$b){
	$charseTmp = CharsetMod($b);
	$tmp = 0;
	$res ="";
	for($n = 0,$c=0;$n<strlen($a);$n++,$c=($c+1)&3){
		switch($c){
			case 0:
			$tt = ord(substr($a,$n,1));
			$res.=substr($charseTmp,($tmp^($tt>>2)),1);
			$tmp = ($tt&3)<<4;
			break;

			case 1:
			$tt = ord(substr($a,$n,1));
			$res.=substr($charseTmp,($tmp^($tt>>4)),1);
			$tmp = ($tt&15)<<2;
			break;
			case 2:
			$tt = ord(substr($a,$n,1));
			$res.=substr($charseTmp,($tmp^($tt>>6)),1);
			$tmp = ($tt&63);
			break;
			case 3:
			$n--;
			$tt = 0;
			$res.=substr($charseTmp,($tmp^$tt),1);
			$tmp = 0;
			break;
		}
	}
	$res.=substr($charseTmp,$tmp,1);
	return $res;
}
function DesBase64($a,$b){
	$charseTmp = CharsetMod($b);
	$tmp = 0;
	$res ="";
	for($n = 0;$n<strlen($a);$n++,$c =($c+1)%3){
		switch($c){
			case 0 :
			$tt = strpos($charseTmp,substr($a,$n,1));
			$n++;
			$ttt = strpos($charseTmp,substr($a,$n,1));
			$res.=chr(($tt<<2)^($ttt>>4));
			$tmp = ($ttt&15)<<4;
			break;
			case 1:
			$tt = strpos($charseTmp,substr($a,$n,1));
			$res.=chr($tmp^($tt>>2));
			$tmp = ($tt&3)<<6;
			break;
			case 2:
			$tt = strpos($charseTmp,substr($a,$n,1));
			$res.=chr($tmp^$tt);
			$tmp = 0;
			break;
		}
	}
	return $res;
}
/*
Problemas gilipollas porque no me compara bien o alguna mierda tiene que tar pasando...GILIPOLLAS
*/
function ModCer($a,$b){
	if(comparar($a,$b)<0)return "0<->".$a;
	$indiceBajada = 1;
	$tmp=substr($a,0,1);
	$quot = "";
	$rem = "";
	while($indiceBajada<strlen($a)){
		while($indiceBajada<strlen($a) && comparar($b,$tmp)>0)$tmp.=substr($a,$indiceBajada++,1);
		$pe = 1;
		$tt = 0;
		if(strlen(DesPad($tmp))==0){
			$quot.='0';
			$rem = "0";
			continue;
		}
		while(($tt=comparar(multiplicar($b,''.$pe),$tmp))<=0)$pe++;
		$quot.=($pe-1);
		$rem = restarAbs($tmp,multiplicar($b,($pe-1).''));
		$tmp = $rem;
	}
	return $quot.'.'.$rem;
}
function Sumar($a,$b){
	$co = comparar($a,$b);
	switch($co){
		case 0:
		break;
		case 1:
		while(strlen($b)<strlen($a))$b = '0'.$b;
		break;
		case -1:
		while(strlen($a)<strlen($b))$a = '0'.$a;
		break;
	}
	$acc = 0;
	$res = "";
	for($n = strlen($a)-1;$n>-1;$n--){
		$p = ord(substr($a,$n,1))-ord('0');
		$s = ord(substr($b,$n,1))-ord('0');
		$tmp = $p+$s+$acc;
		$res = chr(ord('0')+$tmp%10).$res;
		$acc = $tmp/10;
	}
	if($acc!=0)$acc.$res;
	return $res;
}

function restarAbs($a,$b){
	if(comparar($a,$b)==-1)return restarAbs($b,$a);
	while(strlen($b)<strlen($a))$b = '0'.$b;
	$acc = 0;
	$res = "";
	for($n = strlen($a)-1;$n>-1;$n--){
		$tmp = ord(substr($a,$n,1))-ord(substr($b,$n,1))+$acc;
		if($tmp<0){
			$tmp+=10;
			$res = $tmp.$res;
			$acc=-1;
		}else{
			$res = $tmp.$res;
			$acc = 0;
		}
	}
	return $res;
}
function DesPad($a){
	while(strlen($a)>1 && ord(substr($a,0,1))==ord('0'))
		$a = substr($a,1,strlen($a)-1);
	return $a;
}
function comparar($a,$b){
	$a = DesPad($a);
	$b = DesPad($b);
	if(strlen($a)>strlen($b)){
		return 1;
	}else{
		if(strlen($a)<strlen($b))
			return -1;
		else{
			for($n = 0;$n<strlen($a);$n++){
				if(ord(substr($a,$n,1))==ord(substr($b,$n,1)))continue;
				else{
					if(ord(substr($a,$n,1))>ord(substr($b,$n,1)))return 1;
					else
						return -1;
				}
			}
		}
	}
	return 0;
}
/*
Problemas no entiendo que mierda pasa
*/
function multiplicar($a,$b){
	if(comparar($a,$b)<0)return multiplicar($b,$a);
	$tmp = "0";
	$acc;
	for($n = strlen($b)-1,$c=0;$n>-1;$n--,$c++){
		$tt ="";
		$acc = 0;
		for($m = strlen($a)-1;$m>-1;$m--){
			$t = $acc+(ord(substr($a,$m,1))-ord('0'))*(ord(substr($b,$n,1))-ord('0'));
			$tt = ($t%10)."".$tt;
			$acc = floor($t/10);
		}
		if($acc!=0)$tt = $acc.''.$tt;
		for($d = 0;$d<$c;$d++)$tt.='0';
			$tmp = Sumar($tmp,$tt);
	}
//if($acc!=0)$tmp=$acc.$tmp;
	return $tmp;
}
function ksa($clave){
	global $ps,$aR,$bR;
	$aR = 0;
	$bR = 0;
	if(count($ps)==256){
		for($n = 0;$n<256;$n++)
			$ps[$n]=$n;
	}else{
		for($n = 0;$n<256;$n++)
			$ps[]=$n;
	}
	$j = 0;
	for($n=0;$n<256;$n++){
		$j = ($j+$ps[$n]+ord(substr($clave,($n%strlen($clave)),($n%strlen($clave))+1)))%256;
		$tmp = $ps[$n];
		$ps[$n]=$ps[$j];
		$ps[$j]=$tmp;
	}
}
function PRNG(){
	global $ps,$aR,$bR;
	$aR = ($aR+1)%256;
	$bR = ($bR+$ps[$aR])%256;
	$tmp = $ps[$aR];
	$ps[$aR]=$ps[$bR];
	$ps[$bR] = $tmp;
//echo $ps[$aR].'<->'.$ps[($ps[$aR]+$ps[$bR])%256].'<br>';
	return $ps[($ps[$aR]+$ps[$bR])%256];
}
function RC4($a,$b){
	$res = array();
	ksa($b);
	for($n = 0;$n<strlen($a);$n++)
		$res[] = ord(substr($a,$n,$n+1))^PRNG();
	return $res;
}
function DesRC4($a,$b){
	$res = "";
	ksa($b);
//echo '<br>'.count($a).'<br>';
	for($n = 0;$n<count($a);$n++)
		$res.= chr($a[$n]^PRNG());
	return $res;
}
/*
Funciones Personales id unico entre uno y otro
idd es el id face de el que escribe el mensaje
idp de el que lee el mensaje.
*/
function Cifr($idd,$idp,$cifr){
	$tmp = RC4($cifr,sha1($idp));
	$tmp2 = "";
	for($n = 0;$n<count($tmp);$n++)
		$tmp2.=chr($tmp[$n]);
	$tal = ModCer($idd,"64"); 
	$cif = DesPad(substr($tal,strpos($tal,"."+1,strlen($tal))));
	if(strlen($cif)==0)$cif="0";
	return Base64($tmp2,(int)$cif);
}
function DesCifr($idd,$idp,$cifr){
	$tal = ModCer($idd,"64");
	$cif = DesPad(substr($tal,strpos($tal,"."+1,strlen($tal))));
	if(strlen($cif)==0)$cif="0";
	$tmp = DesBase64($cifr,(int)$cif);
	$tales;
	for($n = 0;$n<strlen($tmp);$n++)
		$tales[]=ord(substr($tmp,$n,1));
	return DesRC4($tales,sha1($idp));
}
/*
Funciones Estado para un arreglo de id's de lectura y un id de escritura
En el cifrado es un arreglo de los id, en el descifrado es simplemente el id de el que quiere leer
Si uno de los ID no llega a ser numerico, sacar sha1 y pasar el dump en hexa
*/
function CifrEst($idd,$idp,$cifr){
	$pol = "1";
	for($n = 0;$n<count($idp);$n++){
//echo '<br>'.restarAbs($idp[$n],"1024").' '.$n.' '.count($idp).'<br>';
		$pol = multiplicar($pol,restarAbs($idp[$n],"1024"));
	}
	$res = $pol.'-';
	$tal = RC4($cifr,sha1($idd));
	for($n = 0;$n<count($tal);$n++)
		$res.=chr($tal[$n]);
	$m = ModCer($idd,"64");
	$muak = DesPad(substr($m,strpos($m,"."+1),strlen($m)));
	if(strlen($muak)==0)$mual = "0";
	return Base64($res,$muak);
}
function DesCifrEst($idd,$idp,$cifr){
	$tales = restarAbs($idp,"1024");
	$m = ModCer($idd,"64");
	$muak = DesPad(substr($m,strpos($m,"."+1),strlen($m)));
	if(strlen($muak)==0)$mual = "0";
	$desTales=DesBase64($cifr,$muak);
	$je = substr($desTales,0,strpos($desTales,"-"));
	$ty = ModCer($je,DesPad($tales));
	$he = DesPad(substr($ty,strpos($ty,".")+1,strlen($ty)));
//echo '<br>'.$je.'  '.$tales.'  '.$he;
	if(strlen($he)==0 || (strlen($he)==1 && ord(substr($he,0,1))==ord('0'))){
		$ja = substr($desTales,strpos($desTales,"-")+1,strlen($desTales));
		$jo;
		for($n = 0;$n<strlen($ja);$n++)
			$jo[] = ord(substr($ja,$n,1));
		return DesRC4($jo,sha1($idd));
	}else
return "Tu no puedes ver esto";//depende de el idioma, depronto un flag con el idioma

}
?>
