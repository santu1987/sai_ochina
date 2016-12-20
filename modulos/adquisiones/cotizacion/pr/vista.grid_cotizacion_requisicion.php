
<div class="div_busqueda">
<form id="form_pr_cotizacion" name="form_pr_cotizacion" method="post">
<td align="center"><strong>Nº Requisicion</strong>: </td>                  
	           <input type="text" id="cotizacion_pr_requisicion"  
			   jVal="{valid:/^[0-1]{1,60}$/}"
				jValKey="{valid:/[0-1]/}"/>
  &nbsp;&nbsp;<strong>Nº Cotizacion:</strong> 
  <label>
    <input type="text" id="cotizacion_pr_coti"
    jVal="{valid:/^[0-1]{1,60}$/}"
	jValKey="{valid:/[0-1]/}"/>
  &nbsp;</label>
  <strong>Proveedor:</strong>  
  <label>
    <input type="text" id="cotizacion_pr_proveedor" 
    jVal="{valid:/^[A-Za-z]{1,60}$/}"
	jValKey="{valid:/[A-Za-z]/}"/>
    
  </label>
  &nbsp;&nbsp;<strong>Fecha: </strong> 
  <label>
    <input type="text" readonly="readonly" id="cotizacion_pr_fecha" name="cotizacion_pr_fecha" size="10" maxlength="10" jVal="{valid:/^[0-1]{1,60}$/}"
	jValKey="{valid:/[0-1]/}" onclick="ocultar('Calendario');" onchange="alert('aaa');"/>
  </label><a id="cotizacion_pr_calendario" href="modulos/adquisiones/cotizacion/pr/vista_calendario.php" target="Calendario" onclick="mostrar();"><img src="utilidades/jscalendar-1.0/img.gif"/></a>
  </form>
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>
<iframe name="Calendario" id="Calendario" scrolling="no" src="" height="233px" width="250px" style="position:absolute; border:0; top:150px; left:690px; visibility:hidden"></iframe>
<script language="javascript" type="text/javascript">
function mostrar(){
		document.getElementById('Calendario').style.visibility='visible';
}
</script>