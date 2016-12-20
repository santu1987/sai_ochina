<?
	$fecha=date("Y");
?>
<div class="div_busqueda">
<td align="center"><strong>N Cuenta </strong></td>
<input type="text" id="tesoreria_cheques_chequeras_pr_cuenta" name="tesoreria_cheques_chequeras_pr_cuenta"  />					
<td align="center"><strong>A&ntilde;o</strong></td>
<input type="text" id="tesoreria_cheques_chequeras_pr_ano" name="tesoreria_cheques_chequeras_pr_ano" value="<? echo($fecha);?>" />				 

<!-- la tabla donde se creara el grid con clase 'scroll' -->
<table id="list_grid_<?=$_GET[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<!-- el div donde radicaran los botones de control del grid -->
<div id="pager_grid_<?=$_GET[id_grid]?>" class="scroll" style="text-align:center;"></div>