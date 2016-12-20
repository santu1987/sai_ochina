<script type="text/javascript">

$("#consulta_usuario").jqGrid
({ 
	height: 240,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/administracion_sistema/usuario/co/sql.consulta_usuario.php?nd='+new Date().getTime(),
		datatype: "json",
		colNames:['Usuario','Nombre','Apellido'],
		colModel:[
			{name:'usuario',index:'usuario', width:200},
			{name:'nombre',index:'nombre', width:250},
			{name:'apellido',index:'apellido', width:250},
		],
	pager: jQuery('#consultausuario'),
   	rowNum:20,
   	//rowList:[20,50,100],
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'usuario',
    viewrecords: true,
    sortorder: "asc"
});
var timeoutHnd;
var flAuto = true;

function usuario_doSearch(ev){
	if(!flAuto)
		return;
//	var elem = ev.target||ev.srcElement;
	if(timeoutHnd)
		clearTimeout(timeoutHnd)
	timeoutHnd = setTimeout(usuario_gridReload,100)
	
}
function usuario_gridReload(){
	var usuario_usuario = jQuery("#usuario_usuario").val();
	var nombre_usuario = jQuery("#nombre_usuario").val();
	jQuery("#consulta_usuario").setGridParam({url:"modulos/administracion_sistema/usuario/co/sql.consulta_usuario.php?usu_usua="+usuario_usuario+"&nomb_usua="+nombre_usuario,page:1}).trigger("reloadGrid"); 
}
//function enableAutosubmit(state){
	//flAuto = state;
	//$("#usuario_co_btn_consultar").attr("disabled",state);
//}
$('#usuario_usuario').alpha({nocaps:true});
$('#nombre_usuario').alpha({nocaps:true});

</script>
<SCRIPT LANGUAJE="JavaScript">
function habilitaDeshabilita(form) {
if(form.R1[0].checked == true) {
    form.nombre_usuario.disabled = true;
	form.usuario_usuario.disabled = false;
    }
if(form.R1[1].checked == true) {
    form.nombre_usuario.disabled = false;
	form.usuario_usuario.disabled = true;
    }
}
</SCRIPT>

<table style="width:715;" class="cuerpo_formulario">
	<tr>
		<th width="690px" class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Usuario </th>
	</tr>
	<tr>	
		<th bgcolor="#BADBFC">
		  <div>
          <form name="prueba" id="prueba">
          <input name="R1" type="radio" onchange="habilitaDeshabilita(this.form)" value="Manual">
		    Usuario&nbsp; 
			   <input type="text" name="usuario_usuario" id="usuario_usuario" disabled="false" onkeydown="usuario_doSearch(arguments[0]||event)" /> 
&nbsp;

			  <input name="R1" type="radio" onchange="habilitaDeshabilita(this.form)" value="Automatico" checked="checked"> Nombre&nbsp; 
   <input type="text" name="nombre_usuario" id="nombre_usuario" onkeydown="usuario_doSearch(arguments[0]||event)" />  

		      
		       <label></label>
		  </form></div>
			<table id="consulta_usuario" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="consultausuario" class="scroll" style="text-align:center;"></div> 
		</th>
	</tr>
</table>


