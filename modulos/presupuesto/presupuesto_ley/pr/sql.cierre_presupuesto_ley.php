<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);


$sql="
			SELECT 
				id_presupuesto_ley,
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
				presupuesto_ley  
			WHERE 
				id_organismo=$_SESSION[id_organismo] AND 
				".(($_POST[cierre_presupuesto_ley_pr_id_unidad_ejecutora])?" id_unidad_ejecutora=$_POST[cierre_presupuesto_ley_pr_id_unidad_ejecutora] AND ":"")."
				anio='$_POST[cierre_presupuesto_ley_pr_cmb_ano]' AND 
				estatus=1;
			";
$rs_modulo =& $conn->Execute($sql);
if (!$rs_modulo->EOF)
{
	$conn->StartTrans();
	while (!$rs_modulo->EOF) {
		$sql2="	
					INSERT INTO
						\"presupuesto_ejecutadoR\" (
							id_organismo,
							id_unidad_ejecutora,
							id_proyecto,
							id_accion_centralizada,
							id_accion_especifica,
							ano,
							partida,
							generica,
							especifica,
							sub_especifica,
							monto_presupuesto,
							monto_precomprometido,
							monto_comprometido,
							monto_causado,
							monto_traspasado,
							monto_modificado,
							monto_pagado,
							ultimo_usuario,
							fecha_actualizacion
						)
						VALUES(
							".$rs_modulo->fields("id_organismo")." ,
							".$rs_modulo->fields("id_unidad_ejecutora")." ,
							".$rs_modulo->fields("id_proyecto")." ,
							".$rs_modulo->fields("id_accion_central")." ,
							".$rs_modulo->fields("id_accion_especifica").",
							'".$rs_modulo->fields("anio")."',
							'".$rs_modulo->fields("partida")."',
							'".$rs_modulo->fields("generica")."',
							'".$rs_modulo->fields("especifica")."',
							'".$rs_modulo->fields("sub_especifica")."',
								'{".$rs_modulo->fields("enero").",
								".$rs_modulo->fields("febrero").",
								".$rs_modulo->fields("marzo").",
								".$rs_modulo->fields("abril").",
								".$rs_modulo->fields("mayo").",
								".$rs_modulo->fields("junio").",
								".$rs_modulo->fields("julio").",
								".$rs_modulo->fields("agosto").",
								".$rs_modulo->fields("septiembre").",
								".$rs_modulo->fields("octubre").",
								".$rs_modulo->fields("noviembre").",
								".$rs_modulo->fields("diciembre")."}',
								'{0,0,0,0,0,0,0,0,0,0,0,0}',
								'{0,0,0,0,0,0,0,0,0,0,0,0}',
								'{0,0,0,0,0,0,0,0,0,0,0,0}',
								'{0,0,0,0,0,0,0,0,0,0,0,0}',
								'{0,0,0,0,0,0,0,0,0,0,0,0}',
								'{0,0,0,0,0,0,0,0,0,0,0,0}',
							$_SESSION[id_usuario],
							'".date("Y-m-d H:i:s")."'
						);			
						
					UPDATE 
						presupuesto_ley 
					SET
						estatus = 2 
					WHERE 
						id_presupuesto_ley=".$rs_modulo->fields("id_presupuesto_ley")."
					";
		if (!$conn->Execute($sql2)) 	{
			$conn->CompleteTrans();
			die ('<div id="mensaje"><p>Error al Procesar, no se efectuo el cierre de prespuesto de ley: '.$conn->ErrorMsg().'</p></div>');
		}
		
		$rs_modulo->MoveNext();
	}
	$conn->CompleteTrans();
	die("Ok");
}
else
{
	die("EOF");
	//die ($sql);

}
?>