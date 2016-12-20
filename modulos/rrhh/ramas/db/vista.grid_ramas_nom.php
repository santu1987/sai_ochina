<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT *FROM nivel_academico";
$rs_nivel =& $conn->Execute($sql);
while (!$rs_nivel->EOF){
	$opt_nivel.="<option value='".$rs_nivel->fields("id_nivel_academico")."' >".$rs_nivel->fields("nombre")."</option>";
	$rs_nivel->MoveNext();
} 
?>
<div class="div_busqueda">
<td align="center"><strong>Nivel Academico </strong>: </td>                  
	             <select id="ramas_db_nombre_niv">
	               <option value="">---- Todos ----</option>
	               <?= $opt_nivel?>
    </select>
<td align="center"><strong>Rama </strong>: </td>
<input type="text" id="ramas_db_nombre" maxlength="30"  
			   jval="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jvalkey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"/>
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>
