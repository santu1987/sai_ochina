<div class="div_busqueda" style="padding:5px;">
    <strong>Cedula del Beneficiario: </strong>
    <input type="text" id="beneficiario_busq_nombre_usuario"  name="beneficiario_busq_nombre_usuario"
    jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
    jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}" value="<?=$_POST[busq_cedula]?>" <?=(($_POST[busq_cedula])?'disabled="disabled"':'')?>/>                
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>