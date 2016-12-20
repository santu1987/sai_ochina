<script src="../../Copia de modulo/co/utilidades/jqgrid_demo/js/jquery.jqGrid.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#list3").jqGrid
	({
		height:270,
		recordtext:"Registro(s)",
		loadtext: "Recuperando Información del Servidor",		
		url:'modulos/administracion_sistema/modulo/co/sql.consulta.php?nd='+new Date().getTime(),
		datatype: "json",
		colNames:['Id','Nombre','Pagina','Icono'],
		colModel:[
			{name:'id',index:'id', width:50},
			{name:'nombre',index:'nombre', width:220},
			{name:'pagina',index:'pagina', width:220},
			{name:'icono',index:'icono', width:200}
		],
		pager: $('#pager3'),
		rowNum:20,
		rowList:[20,50,100],
		imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/images',
		sortname: 'nombre',
		viewrecords: true,
		sortorder: "asc"
	});
});
</script>
</head>
<!-- la tabla donde se creara el grid con clase 'scroll' -->
<table id="list3" class="scroll" cellpadding="0" cellspacing="0"></table>
<!-- el div donde radicaran los botones de control del grid -->
<div id="pager3" class="scroll" style="text-align:center;"></div>
