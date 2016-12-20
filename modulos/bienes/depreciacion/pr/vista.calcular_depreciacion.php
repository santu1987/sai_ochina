<?php
session_start();
require_once("../../../../controladores/db.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 

<html> 
<head>
<title>Crear input file</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>
<body>


<table class="cuerpo_formulario">
<tr>
			<th class="titulo_frame" colspan="3">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Depreciacion del Activo			</th>
	</tr>
    	<tr>
			<th>Bien</th>
		  <td>
         	<?php
	$db = dbconn("pgsql");
	$cn = pg_connect("host=".$db["host"]." port=".$db["port"]." dbname=".$db["dbname"]." user=".$db["user"]." password=".$db["password"]);		
	//$cn = pg_connect("host=localhost user=postgres password=batusay dbname=sai_ochina");
//****************** dia, mes y año actual *********************
	$da = date("d");
	$ma = date("m");
	$aa = date("Y");
//******************* SQL para buscar los bienes *******************
	$sql = "SELECT id_bienes, nombre, fecha_compra, valor_compra, vida_util, valor_rescate FROM bienes";
	$query = pg_query($cn,$sql);
	while ($bus=pg_fetch_row($query)){
		echo "bien: ".$bus[1]."<br>";
	//$fecha_compra = substr($bus[2],8,2)."".substr($bus[2],4,4)."".substr($bus[2],0,4);
	$acumulada = 0;
	$libros = 0;
	$vida_util = $bus[4]; 
	$aini = substr($bus[2],0,4);	
	$mini =substr($bus[2],5,2);
	$valor_compra = substr($bus[3],1, strlen($bus[3])); 
	$valor_compra = str_replace('.','',$valor_compra);
	$valor_compra = str_replace(',','.',$valor_compra);
	$valor_rescate = substr($bus[5],1, strlen($bus[5]));
	$valor_rescate = str_replace('.','',$valor_rescate);
	$valor_rescate = str_replace(',','.',$valor_rescate);
	$valor = $valor_compra - $valor_rescate;
	
	//
	//****************//
		$sql_mej = "SELECT valor_rescate, vida_util FROM mejoras WHERE id_bienes = $bus[0]";
		$q_mej = pg_query($cn, $sql_mej);
		$valor_mejoras = 0;
		while($mej=pg_fetch_array($q_mej)){
			$mejoras = substr($mej[0],1, strlen($mej[0])); 
			$mejoras = str_replace('.','',$mejoras);
			$mejoras = str_replace(',','.',$mejoras);
			$valor_mejoras = $valor_mejoras + $mejoras;
			//echo "mejora: ".$valor_mejoras."<br>";
		}
		$valor = $valor + $valor_mejoras;
	//****************//
	//
	
	$valor_depreciacion = $valor / $vida_util;
	echo "valor: ".$valor."<br>";
	echo "vida util: ".$vida_util."<br>";
	echo "valor depreciacion: ".$valor_depreciacion."<br>";
	//$valor_depreciacion = $valor_depreciacion / $vida_util;
	//$valor_depreciacion = $valor_depreciacion / $vida_util;
	/*INICIO*/
	
	/*FIN*/		
	if ($aini==$aa && substr($bus[2],8,2)<=$da){
		$c=1;
		$meses = $ma-$mini;//por ahora na de na
		$mini = $mini+1;
		if(substr($bus[2],8,2)<=15){
			$mini = $mini-1;
		}
		for($i=$mini; $i<=$ma; $i++){
			//
			$sql = "SELECT valor_depreciacion_acumula, valor_libros, valor_depreciacion_mensual FROM depreciacion_mensual WHERE id_bienes = $bus[0]";
	$q = pg_query($cn, $sql);
	while($prueba=pg_fetch_row($q)){
		$acumulada = $prueba[0];
		$libros = $prueba[1];
		$dep = $prueba[2];
	}	
		$acumulada = substr($acumulada, 1, strlen($acumulada));
		$acumulada = str_replace('.','',$acumulada);
		$acumulada = str_replace(',','.',$acumulada);
		$libros = substr($libros, 1, strlen($libros));
		$libros = str_replace('.','',$libros);
		$libros = str_replace(',','.',$libros);
		echo "acumulada bd: ".$acumulada."<br>";
		echo "Libros bd: ".$libros."<br>";
		//
		if($acumulada!='' && $libros!=''){
			$dep = substr($dep, 1, strlen($dep));
			$dep = str_replace('.','',$dep);
			$dep = str_replace(',','.',$dep);
			$valor_depreciacion = $dep;
		}
		//
	if($acumulada=='')
		$acumulada = 0;
	$acumulada =  $acumulada + $valor_depreciacion;
	if($libros=='')
		$libros = $valor;
		
	$libros = $libros - $valor_depreciacion;
	$valor_depreciacion = str_replace('.',',',$valor_depreciacion);
	$acumulada = str_replace('.',',',$acumulada);
	$libros = str_replace('.',',',$libros);
	echo "acumulada +: ".$acumulada."<br>";
	echo "Libros -: ".$libros."<br>";
	
			//echo $sql."<br>";
			
			//
			if(substr($bus[2],8,2)>28 && $i==2){
				$fecha_depreciacion = "28-".$i."-".substr($bus[2],0,4);
				echo "<br> PASO 1<br>";
			}
			if(substr($bus[2],8,2)<28 && $i==2){
				$fecha_depreciacion = substr($bus[2],8,2)."-".$i."-".substr($bus[2],0,4);
			}
			if(($i==4 || $i==6 || $i==9 || $i==11) && (substr($bus[2],8,2)>30)){
				$fecha_depreciacion = "30-".$i."-".substr($bus[2],0,4);
				echo "<br> PASO 2 <br>";
			}
			if(($i==4 || $i==6 || $i==9 || $i==11) && (substr($bus[2],8,2)<30)){
				$fecha_depreciacion = substr($bus[2],8,2)."-".$i."-".substr($bus[2],0,4);
				echo "<br> PASO 2 <br>";
			}
			if($i!=2 && $i!=4 && $i!=6 && $i!=9 && $i!=11){	
				$fecha_depreciacion = substr($bus[2],8,2)."-".$i."-".substr($bus[2],0,4);
				echo "<br> PASO 3 <br>";
			}

			$sql = "SELECT id_depreciacion_mensual FROM depreciacion_mensual WHERE fecha_depreciacion = '$fecha_depreciacion' AND id_bienes = $bus[0]";
			$query2 = pg_query($cn, $sql);
			$bus2 = pg_fetch_array($query2);
			if($bus2[0]==''){
				$sql = "INSERT INTO depreciacion_mensual (id_bienes, valor_depreciacion_mensual, fecha_depreciacion, valor_depreciacion_acumula, valor_libros, id_organismo, ultimo_usuario) VALUES ($bus[0],'$valor_depreciacion','$fecha_depreciacion','$acumulada', '$libros',$_SESSION[id_organismo],$_SESSION[id_usuario]) ";
				echo $sql."<br>";
				pg_query($cn, $sql);
				
			}
			if($c==$vida_util)
				break;
			$c++;
		}
	}
	elseif($aini<$aa){
		$c = 0;
		$mini=$mini+1;

		if(substr($bus[2],8,2)<=15){
			$mini = $mini-1;
		}
		if($da>=substr($bus[2],8,2)){
		for($i=$aini; $i<=$aa; $i++){
			echo "año: ".$i."<br>";
			if(($i==$aa) && (substr($bus[2],5,2)==12)){
			$mini = 1;
			}

			for($m=$mini; $m<=12; $m++){
				//
				$sql = "SELECT valor_depreciacion_acumula, valor_libros, valor_depreciacion_mensual FROM depreciacion_mensual WHERE id_bienes = $bus[0]";
	$q = pg_query($cn, $sql);
	while($prueba=pg_fetch_row($q)){
		$acumulada = $prueba[0];
		$libros = $prueba[1];
		$dep = $prueba[2];
	}
		$acumulada = substr($acumulada, 1, strlen($acumulada));
		$acumulada = str_replace('.','',$acumulada);
		$acumulada = str_replace(',','.',$acumulada);
		$libros = substr($libros, 1, strlen($libros));
		$libros = str_replace('.','',$libros);
		$libros = str_replace(',','.',$libros);
		//
	if($acumulada!='' && $libros!=''){
		$dep = substr($dep,1,strlen($dep));
		$dep = str_replace('.','',$dep);
		$dep = str_replace(',','.',$dep);
		$valor_depreciacion = $dep;
	}	
	//
	if($acumulada=='')
		$acumulada = 0;
	$acumulada =  $acumulada + $valor_depreciacion;
	if($libros=='')
		$libros = $valor;
	$libros = $libros - $valor_depreciacion;
	$valor_depreciacion = str_replace('.',',',$valor_depreciacion);
	$acumulada = str_replace('.',',',$acumulada);
	$libros = str_replace('.',',',$libros);
	echo "acumulada: ".$acumulada."<br>";
	echo "Libros: ".$libros."<br>";
				
				//
				$c++;
				echo "<br> mes es:".$m."<br>";
				if(substr($bus[2],8,2)>28 && $m==2){
					$fecha_depreciacion = "28-".$m."-".$i;
					echo "<br> PASO 1 <br>";
				}
				if(substr($bus[2],8,2)<28 && $m==2){
					$fecha_depreciacion = substr($bus[2],8,2)."-".$m."-".$i;
				}
				if(($m==4 || $m==6 || $m==9 || $m==11) && (substr($bus[2],8,2)>=30)){
					$fecha_depreciacion = "30-".$m."-".$i;
					echo "<br> PASO 2 <br>";
				}
				if(($m==4 || $m==6 || $m==9 || $m==11)&& (substr($bus[2],8,2)<30)){
					$fecha_depreciacion = substr($bus[2],8,2)."-".$m."-".$i;
					echo "<br> PASO 3 <br>";
				}
				if($m!=2 && $m!=4 && $m!=6 && $m!=9 && $m!=11){
					$fecha_depreciacion = substr($bus[2],8,2)."-".$m."-".$i;
				}
				$sql = "SELECT id_depreciacion_mensual FROM depreciacion_mensual WHERE fecha_depreciacion = '$fecha_depreciacion' AND id_bienes = $bus[0]";
				$query2 = pg_query($cn, $sql);
				$bus2 = pg_fetch_array($query2);
				if($bus2[0]=='' && $c<=$vida_util){
					$sql = "INSERT INTO depreciacion_mensual (id_bienes, valor_depreciacion_mensual, fecha_depreciacion, valor_depreciacion_acumula, valor_libros, id_organismo, ultimo_usuario) VALUES ($bus[0],'$valor_depreciacion','$fecha_depreciacion','$acumulada', '$libros',$_SESSION[id_organismo],$_SESSION[id_usuario])";	
					echo $sql."<br>";
					pg_query($cn, $sql);
				}
				if($m==12)
					$mini=1;
				if ($c==$vida_util)
					break;
				if ($i==$aa && $m==$ma)
					break;
							
			}
		}
		}
	}
}
?>
		  </td>
		</tr>
 
		<tr>
			
		</tr>
		<tr>
			<td colspan="3" class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>

</body>
</html>