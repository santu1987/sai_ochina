<link href="../../../estilos/grid_json.css" rel="stylesheet" type="text/css" media="screen" />
<script src="../../../utilidades/jqgrid_demo/js/jquery.js" type="text/javascript"></script>
<script src="../../../utilidades/jqgrid_demo/js/jquery.jqGrid.js" type="text/javascript"></script>
<script src="../../../recursos/jqgrid_demo/js/bigset.js" type="text/javascript"> </script>

<script type="text/javascript">
jQuery(document).ready(function(){
	var id_grupo = jQuery("#id_grupo").val(); 
	var id_perfil = jQuery("#id_perfil").val(); 
	
	jQuery("#list2").jqGrid
	({
		height:390,
		recordtext:"Registro(s)",
		loadtext: "Recuperando Información del Servidor",		
		url:'grid.const.banco.php',
		datatype: "json",
		colNames:['','Modulo'],
		colModel:[
			{name:'id_perfil_modulo',index:'id_perfil_modulo', width:20, align:'left'},
			{name:'nombre',index:'nombre', width:145, align:'left'},
		],
		pager: jQuery('#pager2'),
		rowNum:100,
		imgpath: '../../../recursos/jqgrid_demo/images',
		sortname: 'Id',
		viewrecords: true,
		sortorder: "asc"	
	});
});

function gridReload()
{ 
	var id_grupo = jQuery("#id_grupo").val(); 
	var id_perfil = jQuery("#id_perfil").val(); 
		
	jQuery("#list2").setUrl('grid.const.perfil_modulo.php?id_perfil='+id_perfil+'&id_grupo='+id_grupo+'&nd='+new Date().getTime()); 
	jQuery("#list2").setPage(1); 
	jQuery("#list2").trigger("reloadGrid"); 
} 
</script>
<table id="list2" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="pager2" class="scroll" style="text-align:center;"></div>	