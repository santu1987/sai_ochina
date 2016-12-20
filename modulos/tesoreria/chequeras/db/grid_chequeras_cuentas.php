<?
	$fecha=date("Y");
?>
<div class="div_busqueda">
<td align="center"><strong>N Cuenta </strong></td>
<input type="text" id="tesoreria_chequeras_db_cuenta" name="tesoreria_chequeras_db_cuenta"  />					
<td align="center"><strong>A&ntilde;o</strong></td>
<input type="text" id="tesoreria_chequeras_db_ano" name="tesoreria_chequeras_db_ano" value="<? echo($fecha);?>" />				 
<!--		        <input name="button" type="button" id="tesoreria_banco_cuenta-busqueda_boton_filtro" value="Buscar" />			    </td>
-->
</div>			
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>
		