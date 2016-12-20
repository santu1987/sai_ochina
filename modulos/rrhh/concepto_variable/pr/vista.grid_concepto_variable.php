<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

//
$sql="SELECT * FROM tipo_nomina ORDER BY id_tipo_nomina";
$rs_tipo =& $conn->Execute($sql);
while (!$rs_tipo->EOF) {
	$opt_tipo.="<option value='".$rs_tipo->fields("id_tipo_nomina")."' >".$rs_tipo->fields("nombre")."</option>";
$rs_tipo->MoveNext();
}
//
?>
<div class="div_busqueda">
<td align="center"><strong>Tipo Nomina </strong>:
  <label>
    <select id="tipo_nomina">
    <?= $opt_tipo ?>
    </select>
  </label></td>
<td align="center"><strong>Concepto </strong>: </td>
<input type="text" id="conceptos_variable_pr_conceptos" maxlength="30"  
			   jval="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jvalkey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"/>                               
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>