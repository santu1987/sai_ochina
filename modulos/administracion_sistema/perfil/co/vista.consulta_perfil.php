<script type="text/javascript">

$("#perfil_solo").jqGrid
({ 
	height: 240,
	width: 600,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",		
	url:'modulos/administracion_sistema/perfil/co/sql.consulta_perfil.php?nd='+new Date().getTime(),
	datatype: "json",
	colNames:['Nombre','Comentario'],
	colModel:[
			{name:'nombre',index:'nombre', width:200},
			{name:'Comentario',index:'Comentario', width:250},
	],
	pager: jQuery('#perfilsolo'),
   	rowNum:12,
   	//rowList:[20,50,100],
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'nombre',
    viewrecords: true,
    sortorder: "asc"
});
var timeoutHnd;
var flAuto = true;

function perfil_doSearch(ev){
	if(!flAuto)		return;
//	var elem = ev.target||ev.srcElement;
	if(timeoutHnd)
		clearTimeout(timeoutHnd)
	timeoutHnd = setTimeout(perifl_gridReload,150)
}
function perifl_gridReload(){
	var perfil_co_nombre_usuario = jQuery("#perfil_co_nombre_usuario").val();
	jQuery("#perfil_solo").setGridParam({url:"modulos/administracion_sistema/perfil/co/sql.consulta_perfil.php?nm_mask="+perfil_co_nombre_usuario,page:1}).trigger("reloadGrid"); 

}
function enableAutosubmit(state){
	flAuto = state;
	jQuery("#perfil_co_btn_consultar").attr("enable",state);
}
</script>
<table style="width:600;" class="cuerpo_formulario">
	<tr>
		<th width="600px" class="titulo_frame" colspan="3"><img src="imagenes/iconos/kappfinder28x28.png" style="padding-right:5px;" align="absmiddle" />Consulta Perfil </th>
	</tr>
	<tr>	
		<th bgcolor="#BADBFC">
			<div class="div_busqueda">
				<label id="" nombre for="perfil_co_nombre_usuario">Nombre:&nbsp;&nbsp;</label>
	      <input name="perfil_co_nombre_usuario" type="text" id="perfil_co_nombre_usuario" onkeydown="perfil_doSearch(arguments[0]||event)" size="40" maxlength="40" />
				&nbsp;&nbsp;

			</div>
			<table id="perfil_solo" class="scroll" cellpadding="0" cellspacing="0"></table>
			<!-- el div donde radicaran los botones de control del grid -->
			<div id="perfilsolo" class="scroll" style="text-align:center;"></div>
		</th>
	</tr>
</table>
