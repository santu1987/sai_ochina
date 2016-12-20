<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);


$sql="
			SELECT 
				id_anteproyecto_presupuesto,
				id_organismo,
				id_unidad_ejecutora,
				id_proyecto,
				id_accion_central,
				id_accion_especifica,
				anio,
				partida,
				generica,
				especifica,
				sub_especifica,
				estatus,
				enero,
				febrero,
				marzo,
				abril,
				mayo,
				junio,
				julio,
				agosto,
				septiembre,
				octubre,
				noviembre,
				diciembre 
			FROM 
				anteproyecto_presupuesto  
			WHERE 
				id_organismo=$_SESSION[id_organismo] AND 
				".(($_POST[pre_pre_presupuesto_ley_pr_id_unidad_ejecutora])?" id_unidad_ejecutora=$_POST[pre_pre_presupuesto_ley_pr_id_unidad_ejecutora] AND ":"")."
				anio='$_POST[pre_pre_presupuesto_ley_pr_cmb_ano]' AND 
				estatus=1;
			";

$rs_modulo =& $conn->Execute($sql);
if (!$rs_modulo->EOF)
{
	$conn->StartTrans();
	while (!$rs_modulo->EOF) {
		$sql2 = "	 
				INSERT INTO 
					precierre_anteproyecto(
						id_organismo, 
						id_accion_central, 
						id_unidad_ejecutora, 
						id_accion_especifica, 					
						id_proyecto, 
						anio, 					
						partida, 
						generica, 					
						especifica, 
						sub_especifica, 					
						enero, 
						febrero, 					
						marzo, 
						abril,				
						mayo, 					
						junio,            		
						julio, 					
						agosto, 					
						septiembre, 					
						octubre, 					
						noviembre, 
						diciembre,					
						fecha_modificacion, 
						ultimo_usuario)
				 VALUES (
						".$rs_modulo->fields("id_organismo")." ,
						".$rs_modulo->fields("id_accion_central")." ,
						".$rs_modulo->fields("id_unidad_ejecutora")." , 
						".$rs_modulo->fields("id_accion_especifica").", 
						".$rs_modulo->fields("id_proyecto")." ,
						'".$rs_modulo->fields("anio")."', 
						'".$rs_modulo->fields("partida")."',
						'".$rs_modulo->fields("generica")."',
						'".$rs_modulo->fields("especifica")."',
						'".$rs_modulo->fields("sub_especifica")."',
						'".$rs_modulo->fields("enero")."',
						'".$rs_modulo->fields("febrero")."',
						'".$rs_modulo->fields("marzo")."',
						'".$rs_modulo->fields("abril")."',
						'".$rs_modulo->fields("mayo")."',
						'".$rs_modulo->fields("junio")."',
						'".$rs_modulo->fields("julio")."',
						'".$rs_modulo->fields("agosto")."',
						'".$rs_modulo->fields("septiembre")."',
						'".$rs_modulo->fields("octubre")."',
						'".$rs_modulo->fields("noviembre")."',
						'".$rs_modulo->fields("diciembre")."',
						'".date("Y-m-d H:i:s")."',
						".$_SESSION['id_usuario']."
					);
		
						
						
					UPDATE 
						anteproyecto_presupuesto 
					SET
						estatus = 2 
					WHERE 
						id_anteproyecto_presupuesto=".$rs_modulo->fields("id_anteproyecto_presupuesto")."
					";
		if (!$conn->Execute($sql2)) 	{
			$conn->CompleteTrans();
			die ('<div id="mensaje"><p>Error al Procesar, no se efectuo el cierre de prespuesto de ley: '.$sql2.'</p></div>');
		}
		
		$rs_modulo->MoveNext();
	}
	$conn->CompleteTrans();
	die("Ok");
}
else
{
	die($sql2);
}
?>