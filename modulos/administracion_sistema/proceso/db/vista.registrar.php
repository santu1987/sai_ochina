<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT * FROM modulo WHERE id_grupo=0";
$rs_grupo =& $conn->Execute($sql);
while (!$rs_grupo->EOF) {
	$opt_link.="<option ".(($rs_grupo->fields("id_grupo"))?"style='padding-left:5px;'":"")." value='".$rs_grupo->fields("id")."' >".$rs_grupo->fields("nombre")."</option>";		
	$sql="SELECT * FROM modulo WHERE id_grupo=".$rs_grupo->fields("id");
	$rs_sub_grupo =& $conn->Execute($sql);
	while (!$rs_sub_grupo->EOF) {	
		$opt_link.="<option ".(($rs_sub_grupo->fields("id_grupo"))?"style='padding-left:30px;'":"")." value='".$rs_sub_grupo->fields("id")."' >".$rs_sub_grupo->fields("nombre")."</option>";
		$rs_sub_grupo->MoveNext();
	}
   $rs_grupo->MoveNext();
}
?>
<script type='text/javascript'>
$("#btn_consultar").click(function() {
	var ventana=new Boxy.load("modulos/administracion_sistema/modulo/db/grid.consulta.php",{unloadOnHide:true,modal:true,show:true,title: "Dialog",cache:true});
});
$("#btn_guardar").click(function() {
	$.ajax ({
		url: "modulos/administracion_sistema/modulo/db/sql.registrar.php",
		data:dataForm('modulo'),
		type:'POST',
		cache: false,
		success: function(html)
		{
			getObj('msgAjax_registrarModulo').style.display='';
			if (html=="Ok")
			{
				getObj('msgAjax_registrarModulo').innerHTML="Se Registro Con Exito";
			}
			else
			{
				getObj('msgAjax_registrarModulo').innerHTML=html;
			}
		}
	});
});
</script>

<span class="msg" id="msgAjax_registrarModulo"></span>

<div id="botonera">
	<img id="btn_consultar" src="../../Copia de modulo/db/imagenes/null.gif" />
	<img id="btn_guardar" src="../../Copia de modulo/db/imagenes/null.gif" />
</div>

<form method="post" id="modulo" name="modulo">
<table id="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="../../Copia de modulo/db/imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Proceso </th>
	</tr>
	<tr>
		<th>Nombre:		</th>	
          <td >
            <input name="proceso_db_vista_nombre" type="text" id="proceso_db_vista_nombre"   value="" size="40" maxlength="60">
          </td>
	</tr>	
	<tr>
		<th>Observaci&oacute;n:</th>
         <td >
           <textarea name="obs" cols="60" id="proceso_db_vista_observacion">
           </textarea>
         </td>
	</tr>
	<tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
  </table>
</form>