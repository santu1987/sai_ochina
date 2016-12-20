<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1;
$Sql="
			SELECT 
				usuario  
			FROM 
				usuario 
			WHERE 
				id_usuario = $_SESSION[id_usuario]";
//
//

$row=& $conn->Execute($Sql);
/*if (!$row->EOF)
{
	$count = $row->fields("count");
}*/
$usuario = $row->fields("usuario");
	$sql = "	
				INSERT INTO 
					mejoras 
					(
						id_organismo,
						id_bienes,
						nombre_mejora,
						fecha_mejora,
						valor_rescate,
						vida_util,
						usuario_carga_mejora,
						descripcion_general,
						comentarios
					) 
					VALUES
					(
						'$_SESSION[id_organismo]',
						'$_POST[form_mejoras_bien_pr_id_bienes]',
						'$_POST[form_mejoras_bien_pr_nombre_mejora]',
						'$_POST[form_mejoras_bien_pr_fecha_mejora]',
						'$_POST[form_mejoras_bien_pr_valor_mejora]',
						'$_POST[form_mejoras_bien_pr_vida_util]',
						'$usuario',
						'$_POST[form_mejoras_bien_pr_descripcion]',
						'$_POST[form_mejoras_bien_pr_comentario]'
					)
			";
if ($conn->Execute($sql) == false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
}
else
{
	$sql = "SELECT 
				valor_compra, 
				vida_util
			FROM
				bienes 
			WHERE
				id_bienes = $_POST[form_mejoras_bien_pr_id_bienes]";
	$row =& $conn->Execute($sql);
	$valor_compra = $row->fields("valor_compra");
	$valor_compra = substr($valor_compra,1,strlen($valor_compra));
	$valor_compra = str_replace('.','',$valor_compra);
	$valor_compra = str_replace(',','.',$valor_compra);
	$nuevo_valor_compra = $_POST['form_mejoras_bien_pr_valor_mejora'];
	$nuevo_valor_compra = str_replace('.','',$nuevo_valor_compra);
	$nuevo_valor_compra = str_replace(',','.',$nuevo_valor_compra);
	$valor_compra = $valor_compra + $nuevo_valor_compra;
	$valor_rescate = ($valor_compra * 10)/100;
	$valor_rescate = str_replace('.',',',$valor_rescate);
	$valor_compra = str_replace('.',',',$valor_compra);
	$vida_util = $row->fields("vida_util");
	$vida_util = $vida_util + $_POST['form_mejoras_bien_pr_vida_util'];
	//echo "Valor Compra: ".$valor_compra."<br>";
	//echo "Vida Util: ".$vida_util."<br>";
	$sql = "
	
			UPDATE 
				bienes
			SET	
				vida_util = '$vida_util',
				estatus_bienes=4 
			WHERE 
				id_bienes = $_POST[form_mejoras_bien_pr_id_bienes] ";
	$row =& $conn->Execute($sql);
	$sql = "DELETE FROM
				depreciacion_mensual
			WHERE
				id_bienes = $_POST[form_mejoras_bien_pr_id_bienes]";
	$row =& $conn->Execute($sql);			
	echo ("Registrado");
}
?>