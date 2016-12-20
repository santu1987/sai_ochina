<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT *FROM tipo_nomina";
$rs_tipo_nomina =& $conn->Execute($sql);
while (!$rs_tipo_nomina->EOF){
	$opt_tipo_nomina.="<option value='".$rs_tipo_nomina->fields("id_tipo_nomina")."' >".$rs_tipo_nomina->fields("nombre")."</option>";
	$rs_tipo_nomina->MoveNext();
} 
?>
<div class="div_busqueda">
<td align="center"><strong>Tipo Nomina</strong>: </td>                  
	             <select id="nomina_pr_tip_nomina">
	               <option value="">---- Todos ----</option>
	               <?= $opt_tipo_nomina?>
    </select>
<td align="center"><strong>CI:</strong>: </td>                      
<label>
  <input type="text" id="nomina_pr_ci" maxlength="30"  
			   jval="{valid:/^[V-E - 0-9]{1,60}$/}"
				jvalkey="{valid:/[V-E - 0-9]/}"/>
</label>
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>