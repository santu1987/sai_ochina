<?php
/*function Centenas($VCentena) {
$Numeros[0] = "cero";
$Numeros[1] = "uno";
$Numeros[2] = "dos";
$Numeros[3] = "tres";
$Numeros[4] = "cuatro";
$Numeros[5] = "cinco";
$Numeros[6] = "seis";
$Numeros[7] = "siete";
$Numeros[8] = "ocho";
$Numeros[9] = "nueve";
$Numeros[10] = "diez";
$Numeros[11] = "once";
$Numeros[12] = "doce";
$Numeros[13] = "trece";
$Numeros[14] = "catorce";
$Numeros[15] = "quince";
$Numeros[20] = "veinte";
$Numeros[30] = "treinta";
$Numeros[40] = "cuarenta";
$Numeros[50] = "cincuenta";
$Numeros[60] = "sesenta";
$Numeros[70] = "setenta";
$Numeros[80] = "ochenta";
$Numeros[90] = "noventa";
$Numeros[100] = "ciento";
$Numeros[101] = "quinientos";
$Numeros[102] = "setecientos";
$Numeros[103] = "novecientos";
If ($VCentena == 1) { return $Numeros[100]; }
Else If ($VCentena == 5) { return $Numeros[101];}
Else If ($VCentena == 7 ) {return ( $Numeros[102]); }
Else If ($VCentena == 9) {return ($Numeros[103]);}
Else {return $Numeros[$VCentena];}

}



function Unidades($VUnidad) {
$Numeros[0] = "cero";
$Numeros[1] = "un";
$Numeros[2] = "dos";
$Numeros[3] = "tres";
$Numeros[4] = "cuatro";
$Numeros[5] = "cinco";
$Numeros[6] = "seis";
$Numeros[7] = "siete";
$Numeros[8] = "ocho";
$Numeros[9] = "nueve";
$Numeros[10] = "diez";
$Numeros[11] = "once";
$Numeros[12] = "doce";
$Numeros[13] = "trece";
$Numeros[14] = "catorce";
$Numeros[15] = "quince";
$Numeros[20] = "veinte";
$Numeros[30] = "treinta";
$Numeros[40] = "cuarenta";
$Numeros[50] = "cincuenta";
$Numeros[60] = "sesenta";
$Numeros[70] = "setenta";
$Numeros[80] = "ochenta";
$Numeros[90] = "noventa";
$Numeros[100] = "ciento";
$Numeros[101] = "quinientos";
$Numeros[102] = "setecientos";
$Numeros[103] = "novecientos";

$tempo=$Numeros[$VUnidad];
return $tempo;
}

function Decenas($VDecena) {
$Numeros[0] = "cero";
$Numeros[1] = "uno";
$Numeros[2] = "dos";
$Numeros[3] = "tres";
$Numeros[4] = "cuatro";
$Numeros[5] = "cinco";
$Numeros[6] = "seis";
$Numeros[7] = "siete";
$Numeros[8] = "ocho";
$Numeros[9] = "nueve";
$Numeros[10] = "diez";
$Numeros[11] = "once";
$Numeros[12] = "doce";
$Numeros[13] = "trece";
$Numeros[14] = "catorce";
$Numeros[15] = "quince";
$Numeros[20] = "veinte";
$Numeros[30] = "treinta";
$Numeros[40] = "cuarenta";
$Numeros[50] = "cincuenta";
$Numeros[60] = "sesenta";
$Numeros[70] = "setenta";
$Numeros[80] = "ochenta";
$Numeros[90] = "noventa";
$Numeros[100] = "ciento";
$Numeros[101] = "quinientos";
$Numeros[102] = "setecientos";
$Numeros[103] = "novecientos";
$tempo = ($Numeros[$VDecena]);
return $tempo;
}





function NumerosALetras($Numero){


$Decimales = 0;
//$Numero = intval($Numero);
$letras = "";

while ($Numero != 0){

// '*---> Validación si se pasa de 100 millones

If ($Numero >= 1000000000) {
$letras = "Error en Conversión a Letras";
$Numero = 0;
$Decimales = 0;
}

// '*---> Centenas de Millón
If (($Numero < 1000000000) And ($Numero >= 100000000)){
If ((Intval($Numero / 100000000) == 1) And (($Numero - (Intval($Numero / 100000000) * 100000000)) < 1000000)){
$letras .= (string) "cien millones ";
}
Else {
$letras = $letras & Centenas(Intval($Numero / 100000000));
If ((Intval($Numero / 100000000) <> 1) And (Intval($Numero / 100000000) <> 5) And (Intval($Numero / 100000000) <> 7) And (Intval($Numero / 100000000) <> 9)) {
$letras .= (string) "cientos ";
}
Else {
$letras .= (string) " ";
}
}
$Numero = $Numero - (Intval($Numero / 100000000) * 100000000);
}

// '*---> Decenas de Millón
If (($Numero < 100000000) And ($Numero >= 10000000)) {
If (Intval($Numero / 1000000) < 16) {
$tempo = Decenas(Intval($Numero / 1000000));
$letras .= (string) $tempo;
$letras .= (string) " millones ";
$Numero = $Numero - (Intval($Numero / 1000000) * 1000000);
}
Else {
$letras = $letras & Decenas(Intval($Numero / 10000000) * 10);
$Numero = $Numero - (Intval($Numero / 10000000) * 10000000);
If ($Numero > 1000000) {
$letras .= $letras & " y ";
}
}
}

// '*---> Unidades de Millón
If (($Numero < 10000000) And ($Numero >= 1000000)) {
$tempo=(Intval($Numero / 1000000));
If ($tempo == 1) {
$letras .= (string) " un millón ";
}
Else {
$tempo= Unidades(Intval($Numero / 1000000));
$letras .= (string) $tempo;
$letras .= (string) " millones ";
}
$Numero = $Numero - (Intval($Numero / 1000000) * 1000000);
}

// '*---> Centenas de Millar
If (($Numero < 1000000) And ($Numero >= 100000)) {
$tempo=(Intval($Numero / 100000));
$tempo2=($Numero - ($tempo * 100000));
If (($tempo == 1) And ($tempo2 < 1000)) {
$letras .= (string) "cien mil ";
}
Else {
$tempo=Centenas(Intval($Numero / 100000));
$letras .= (string) $tempo;
$tempo=(Intval($Numero / 100000));
If (($tempo <> 1) And ($tempo <> 5) And ($tempo <> 7) And ($tempo <> 9)) {
$letras .= (string) "cientos ";
}
Else {
$letras .= (string) " ";
}
}
$Numero = $Numero - (Intval($Numero / 100000) * 100000);
}

// '*---> Decenas de Millar
If (($Numero < 100000) And ($Numero >= 10000)) {
$tempo= (Intval($Numero / 1000));
If ($tempo < 16) {
$tempo = Decenas(Intval($Numero / 1000));
$letras .= (string) $tempo;
$letras .= (string) " mil ";
$Numero = $Numero - (Intval($Numero / 1000) * 1000);
}
Else {
$tempo = Decenas(Intval($Numero / 10000) * 10);
$letras .= (string) $tempo;
$Numero = $Numero - (Intval(($Numero / 10000)) * 10000);
If ($Numero > 1000) {
$letras .= (string) " y ";
}
Else {
$letras .= (string) " mil ";

}
}
}


// '*---> Unidades de Millar
If (($Numero < 10000) And ($Numero >= 1000)) {
$tempo=(Intval($Numero / 1000));
If ($tempo == 1) {
$letras .= (string) "un";
}
Else {
$tempo = Unidades(Intval($Numero / 1000));
$letras .= (string) $tempo;
}
$letras .= (string) " mil ";
$Numero = $Numero - (Intval($Numero / 1000) * 1000);
}

// '*---> Centenas
If (($Numero < 1000) And ($Numero > 99)) {
If ((Intval($Numero / 100) == 1) And (($Numero - (Intval($Numero / 100) * 100)) < 1)) {
$letras = $letras & "cien ";
}
Else {
$temp=(Intval($Numero / 100));
$l2=Centenas($temp);
$letras .= (string) $l2;
If ((Intval($Numero / 100) <> 1) And (Intval($Numero / 100) <> 5) And (Intval($Numero / 100) <> 7) And (Intval($Numero / 100) <> 9)) {
$letras .= "cientos ";
}
Else {
$letras .= (string) " ";
}
}

$Numero = $Numero - (Intval($Numero / 100) * 100);

}

// '*---> Decenas
If (($Numero < 100) And ($Numero > 9) ) {
If ($Numero < 16 ) {
$tempo = Decenas(Intval($Numero));
$letras .= $tempo;
$Numero = $Numero - Intval($Numero);
}
Else {
$tempo= Decenas(Intval(($Numero / 10)) * 10);
$letras .= (string) $tempo;
$Numero = $Numero - (Intval(($Numero / 10)) * 10);
If ($Numero > 0.99) {
$letras .=(string) " y ";

}
}
}

// '*---> Unidades
If (($Numero < 10) And ($Numero > 0.99)) {
$tempo=Unidades(Intval($Numero));
$letras .= (string) $tempo;

$Numero = $Numero - Intval($Numero);
}


// '*---> Decimales
If ($Decimales > 0) {

// $letras .=(string) " con ";
// $Decimales= $Decimales*100;
// echo ("*");
// $Decimales = number_format($Decimales, 2);
// echo ($Decimales);
// $tempo = Decenas(Intval($Decimales));
// $letras .= (string) $tempo;
// $letras .= (string) "Centimos";
}
Else {
If (($letras <> "Error en Conversión a Letras") And (strlen(Trim($letras)) > 0)) {
$letras .= (string) " ";

}
}
return $letras;
}
}


//favor de teclear a mano la cantidad numerica a convertir y asignarla a $tt
function numero_to_letras($tt)
{
	$tt 				=	$tt	+	0.009;
	$Numero 		=	intval($tt);
	$Decimales	=	$tt - Intval($tt);
	$Decimales	=	$Decimales*100;
	$Decimales	=	Intval($Decimales);
	$x					=	NumerosALetras($Numero);
	$salida			=	strtoupper($x);
	If ($Decimales > 0)
	{	
		$y		=	NumerosALetras($Decimales);		
		$salida.=	(($salida)?" BOLIVAR".(($Numero>1)?"ES":"")." CON ":"");
		$salida.=	$y;
		$salida.=	" Cts";
	}
	else {
		$salida .=	(($salida)?" BOLIVAR".(($Numero>1)?"ES":"")." CON ":"");
		$salida	.= "0 Cts";
	}
	return $salida;
}*/
?><?php

class EnLetras
{
  var $Void = "";
  var $SP = " ";
  var $Dot = ".";
  var $Zero = "0";
  var $Neg = "Menos";
  
function ValorEnLetras($x, $Moneda) 
{
    $s="";
    $Ent="";
    $Frc="";
    $Signo="";
        
    if(floatVal($x) < 0)
     $Signo = $this->Neg . " ";
    else
     $Signo = "";
    
    if(intval(number_format($x,2,'.','') )!=$x) //<- averiguar si tiene decimales
      $s = number_format($x,2,'.','');
    else
      $s = number_format($x,0,'.','');
       
    $Pto = strpos($s, $this->Dot);
        
    if ($Pto === false)
    {
      $Ent = $s;
      $Frc = $this->Void;
    }
    else
    {
      $Ent = substr($s, 0, $Pto );
      $Frc =  substr($s, $Pto+1);
    }

    if($Ent == $this->Zero || $Ent == $this->Void)
       $s = "Cero ";
    elseif( strlen($Ent) > 7)
    {
       $s = $this->SubValLetra(intval( substr($Ent, 0,  strlen($Ent) - 6))) . 
             "Millones " . $this->SubValLetra(intval(substr($Ent,-6, 6)));
    }
    else
    {
      $s = $this->SubValLetra(intval($Ent));
    }

    if (substr($s,-9, 9) == "Millones " || substr($s,-7, 7) == "Millón ")
       $s = $s . "de ";

    $s = $s . $Moneda.(($x>1)?"ES":"");

    if($Frc != $this->Void)
    {
       $s = $s . " Con " . $this->SubValLetra(intval($Frc)) . "Centimos";
       //$s = $s . " " . $Frc . "/100";
    }
    return ($Signo . $s);
   
}


function SubValLetra($numero) 
{
    $Ptr="";
    $n=0;
    $i=0;
    $x ="";
    $Rtn ="";
    $Tem ="";

    $x = trim("$numero");
    $n = strlen($x);

    $Tem = $this->Void;
    $i = $n;
    
    while( $i > 0)
    {
       $Tem = $this->Parte(intval(substr($x, $n - $i, 1). 
                           str_repeat($this->Zero, $i - 1 )));
       If( $Tem != "Cero" )
          $Rtn .= $Tem . $this->SP;
       $i = $i - 1;
    }

    
    //--------------------- GoSub FiltroMil ------------------------------
    $Rtn=str_replace(" Mil Mil", " Un Mil", $Rtn );
    while(1)
    {
       $Ptr = strpos($Rtn, "Mil ");       
       If(!($Ptr===false))
       {
          If(! (strpos($Rtn, "Mil ",$Ptr + 1) === false ))
            $this->ReplaceStringFrom($Rtn, "Mil ", "", $Ptr);
          Else
           break;
       }
       else break;
    }

    //--------------------- GoSub FiltroCiento ------------------------------
    $Ptr = -1;
    do{
       $Ptr = strpos($Rtn, "Cien ", $Ptr+1);
       if(!($Ptr===false))
       {
          $Tem = substr($Rtn, $Ptr + 5 ,1);
          if( $Tem == "M" || $Tem == $this->Void)
             ;
          else          
             $this->ReplaceStringFrom($Rtn, "Cien", "Ciento", $Ptr);
       }
    }while(!($Ptr === false));

    //--------------------- FiltroEspeciales ------------------------------
    $Rtn=str_replace("Diez Un", "Once", $Rtn );
    $Rtn=str_replace("Diez Dos", "Doce", $Rtn );
    $Rtn=str_replace("Diez Tres", "Trece", $Rtn );
    $Rtn=str_replace("Diez Cuatro", "Catorce", $Rtn );
    $Rtn=str_replace("Diez Cinco", "Quince", $Rtn );
    $Rtn=str_replace("Diez Seis", "Diez y Seis", $Rtn );
    $Rtn=str_replace("Diez Siete", "Diez y Siete", $Rtn );
    $Rtn=str_replace("Diez Ocho", "Diez y Ocho", $Rtn );
    $Rtn=str_replace("Diez Nueve", "Diez y Nueve", $Rtn );
    $Rtn=str_replace("Veinte Un", "Veinte y Un", $Rtn );
    $Rtn=str_replace("Veinte Dos", "Veinte y Dos", $Rtn );
    $Rtn=str_replace("Veinte Tres", "Veinte y Tres", $Rtn );
    $Rtn=str_replace("Veinte Cuatro", "Veinte y Cuatro", $Rtn );
    $Rtn=str_replace("Veinte Cinco", "Veinte y Cinco", $Rtn );
    $Rtn=str_replace("Veinte Seis", "Veinte y Seís", $Rtn );
    $Rtn=str_replace("Veinte Siete", "Veinte y Siete", $Rtn );
    $Rtn=str_replace("Veinte Ocho", "Veinte y Ocho", $Rtn );
    $Rtn=str_replace("Veinte Nueve", "Veinte y Nueve", $Rtn );

    //--------------------- FiltroUn ------------------------------
    If(substr($Rtn,0,1) == "M") $Rtn = "Un " . $Rtn;
    //--------------------- Adicionar Y ------------------------------
    for($i=65; $i<=88; $i++)
    {
      If($i != 77)
         $Rtn=str_replace("a " . Chr($i), "* y " . Chr($i), $Rtn);
    }
    $Rtn=str_replace("*", "a" , $Rtn);
    return($Rtn);
}


function ReplaceStringFrom(&$x, $OldWrd, $NewWrd, $Ptr)
{
  $x = substr($x, 0, $Ptr)  . $NewWrd . substr($x, strlen($OldWrd) + $Ptr);
}


function Parte($x)
{
    $Rtn='';
    $t='';
    $i='';
    Do
    {
      switch($x)
      {
         Case 0:  $t = "Cero";break;
         Case 1:  $t = "Un";break;
         Case 2:  $t = "Dos";break;
         Case 3:  $t = "Tres";break;
         Case 4:  $t = "Cuatro";break;
         Case 5:  $t = "Cinco";break;
         Case 6:  $t = "Seis";break;
         Case 7:  $t = "Siete";break;
         Case 8:  $t = "Ocho";break;
         Case 9:  $t = "Nueve";break;
         Case 10: $t = "Diez";break;
         Case 20: $t = "Veinte";break;
         Case 30: $t = "Treinta";break;
         Case 40: $t = "Cuarenta";break;
         Case 50: $t = "Cincuenta";break;
         Case 60: $t = "Sesenta";break;
         Case 70: $t = "Setenta";break;
         Case 80: $t = "Ochenta";break;
         Case 90: $t = "Noventa";break;
         Case 100: $t = "Cien";break;
         Case 200: $t = "Doscientos";break;
         Case 300: $t = "Trescientos";break;
         Case 400: $t = "Cuatrocientos";break;
         Case 500: $t = "Quinientos";break;
         Case 600: $t = "Seiscientos";break;
         Case 700: $t = "Setecientos";break;
         Case 800: $t = "Ochocientos";break;
         Case 900: $t = "Novecientos";break;
         Case 1000: $t = "Mil";break;
         Case 1000000: $t = "Millón";break;
      }

      If($t == $this->Void)
      {
        $i = $i + 1;
        $x = $x / 1000;
        If($x== 0) $i = 0;
      }
      else
         break;
           
    }while($i != 0);
   
    $Rtn = $t;
    Switch($i)
    {
       Case 0: $t = $this->Void;break;
       Case 1: $t = " Mil";break;
       Case 2: $t = " Millones";break;
       Case 3: $t = " Billones";break;
    }
    return($Rtn . $t);
}

}

function numero_to_letras($tt)
{
	$V=new EnLetras();
	return $V->ValorEnLetras($tt,"BOLIVAR");
}
?>