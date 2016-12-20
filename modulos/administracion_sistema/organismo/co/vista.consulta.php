<script type="text/javascript">

$("#consulta_organismo").jqGrid
({ 
	height: 250,
	width: 600,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/administracion_sistema/organismo/co/sql.consulta.php?nd='+new Date().getTime(),
		datatype: "json",
		colNames:['Nombre','Página Web','Correo','Telefono'],
		colModel:[
			{name:'nombre',index:'nombre', width:200},
			{name:'pagina_web',index:'pagina_web', width:200},
			{name:'email',index:'email', width:200},
			{name:'telefono',index:'telefono', width:200},
		],
	pager: jQuery('#consultaorganismo'),
   	rowNum:20,
   	//rowList:[20,50,100],
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'nombre',
    viewrecords: true,
    sortorder: "asc"
});

var timeoutHnd;
var flAuto = true;

function organismo_doSearch(ev)
{
	if(!flAuto)	return;
//	var elem = ev.target||ev.srcElement;
	if(timeoutHnd)
		clearTimeout(timeoutHnd)
		timeoutHnd = setTimeout(organismo_gridReload,150)
}

function organismo_gridReload()
{
	var nombre_organismo = jQuery("#nombre_organismo").val();
	jQuery("#consulta_organismo").setGridParam({url:"modulos/administracion_sistema/organismo/co/sql.consulta.php?nomb_org="+nombre_organismo,page:1}).trigger("reloadGrid"); 
}

function enableAutosubmit(state)
{
	flAuto = state;
	jQuery("#organismo_co_btn_consultar").attr("enable",state); 
}
$('#nombre_organismo').alpha({allow:' '});
</script>


<table style="width:600;" class="cuerpo_formulario">
	<tr>
		<th width="600px" class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Organismo </th>
	</tr>
	<tr>	
		<th bgcolor="#BADBFC">
			<div>  
			   Nombre &nbsp; <input name="nombre_organismo" type="text" id="nombre_organismo" onkeydown="organismo_doSearch(arguments[0]||event)" size="40" maxlength="40" />  
			   &nbsp;
			   
      </div>
			<table id="consulta_organismo" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="consultaorganismo" class="scroll" style="text-align:center;"></div> 
		</th>
	</tr>
</table>


