
<div class="div_busqueda">
<td align="center"><strong>N&deg; Documento</strong>: </td> 
<input type="text" id="parametro_cxp_pr_documento" name="parametro_cxp_pr_documento" 
   jVal="{valid:/^[0-9]{1,60}$/}"
   jValKey="{valid:/[0-9]/}"/>
<td align="center"><strong>Buque</strong>: </td>                  
  <input type="text" id="parametro_cxp_pr_nombre" name="parametro_cxp_pr_nombre" 
   jVal="{valid:/^[a-zA-Z0-9 áéíóúÁÉÍÓÚñ.]{1,60}$/}"
   jValKey="{valid:/[a-zA-Z0-9 áéíóúÁÉÍÓÚñ.]/}"/>

</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>