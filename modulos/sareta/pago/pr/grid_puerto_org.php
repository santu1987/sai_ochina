<div class="div_busqueda">
<td align="center"><strong>Puerto</strong>: </td>                  
  <input type="text" id="parametro_cxp_pr_nombre_puerto"  
   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
   jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"/>
   <input type="hidden" name="bandera_org" id="bandera_org" value="<?=$_POST['id_bandera']?>" />
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>