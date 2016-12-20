<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<style type="text/css">
<!--
body,td,th {
	color: #000;
	font-weight: bold;
	font-size:10px;
}
-->
</style></head>

<body>
<img src="../../../../imagenes/iconos/imprimir.png" border="0" style=" width:40px; height:40px; margin-left:92%; position:fixed; solid; background-color:#83B4D8; cursor:pointer" onclick="window.print();" />
<br /><br /><br /><br />
<table id="codigos" width='100%' style="border:#000 ridge 1px">
	<tr style="border:#000 ridge 1px">
<?php 
$conexion=pg_connect("host=localhost user=postgres port=5432 dbname=sai_ochina password=batusay");
if($_GET['id_bienes']==""){
	$sql = "	SELECT 
						count(id_bienes) as total
					FROM
						bienes
				 WHERE 
				 	fecha_compra>='$_GET[fecha_desde]'
					";
		$query= pg_query($conexion,$sql);
		$row= pg_fetch_array($query);
		$total=$row["total"];
		$sql2 = "	SELECT 
						nombre,
						codigo_bienes as codigo
					FROM
						bienes
					WHERE 
				 	fecha_compra>='$_GET[fecha_desde]' AND fecha_compra<='$_GET[fecha_hasta]'
					ORDER BY
						id_bienes
					";
		$query2= pg_query($conexion,$sql2);
		$con=0;
		while($row2= pg_fetch_array($query2))
		 {
			$nombre=$row2["nombre"];
			$codigo=$row2["codigo"];
		$ini=0;
		$tam=18;
				$con++;
				echo "<td style='border:#000 ridge 1px'>
				<img src='../../../../imagenes/logos/logo_ochina.png' width='35' height='35' style='padding-left:15px; padding-top:5px;' />&nbsp;".strtoupper($nombre)."
				<br><img src='../db/barcode.php?bdata=$codigo' />
				</td>";
				if($con==3){
					$con=0;
					echo "</tr>";
					echo "<tr>";
				}
				if($t==$tam){
					echo "</tr>";
				}
		}
}
else{
	$sql = "	SELECT 
						count(id_bienes) as total
					FROM
						bienes
				 WHERE 
				 	id_bienes=$_GET[id_bienes]
					";
		$query= pg_query($conexion,$sql);
		$row= pg_fetch_array($query);
		$total=$row["total"];
		$sql2 = "	SELECT 
						nombre,
						codigo_bienes as codigo
					FROM
						bienes
					WHERE 
				 	id_bienes=$_GET[id_bienes]
					ORDER BY
						id_bienes
					";
		$query2= pg_query($conexion,$sql2);
		$con=0;
		while($row2= pg_fetch_array($query2))
		 {
			$nombre=$row2["nombre"];
			$codigo=$row2["codigo"];
		$ini=0;
		$tam=18;
				$con++;
				echo "<td style='border:#000 ridge 1px'>
				<img src='../../../../imagenes/logos/logo_ochina.png' width='35' height='35' style='padding-left:15px; padding-top:5px;' />&nbsp;".strtoupper($nombre)."
				<br><img src='../db/barcode.php?bdata=$codigo' />
				</td>";
				if($con==3){
					$con=0;
					echo "</tr>";
					echo "<tr>";
				}
				if($t==$tam){
					echo "</tr>";
				}
		}
}
?>
</table>
</body>
</html>