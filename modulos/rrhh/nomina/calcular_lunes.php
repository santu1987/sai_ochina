<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body>
<?php
function lunes($fechaInicio,$fechaFin)
{
	$dias=array();
	$fecha1=date($fechaInicio);
	$fecha2=date($fechaFin);
	$fechaTime=strtotime("-1 day",strtotime($fecha1));//Les resto un dia para que el next sunday pueda evaluarlo en caso de que sea un domingo
	$valor=0;
	$fecha=date("Y-m-d",$fechaTime);
	while($fecha <= $fecha2)
	{
		$proximo_lunes=strtotime("next Monday",$fechaTime);
		$fechaLunes=date("Y-m-d",$proximo_lunes);
		if($fechaLunes <= $fechaFin)
		{	
			$dias[$fechaLunes]=$fechaLunes;
		}
		else
		{
			break;
		}
		$fechaTime=$proximo_lunes;
		$fecha=date("Y-m-d",$proximo_lunes);
		$valor++;
	}
	echo $valor;
	return $dias;
}//fin de domingos
$datos=lunes("2010-04-01","2010-04-30"); //creo un array que tendra las fechas
?>
</body>
</html>