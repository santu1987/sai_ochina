<link rel="stylesheet" href="utilidades/jquery.tabs/jquery.tabs.css" type="text/css">

<script src="utilidades/jquery.tabs/jquery.tabs.pack.js" type="text/javascript"></script>

<script type="text/javascript">
	$(function() {

		$('#container-1').tabs();

	});
	
	$("#list_consulta_programa").jqGrid
	({ 
		height: 315,
		width: 650,
		recordtext:"Consulta(s)",
		loadtext: "Recuperando Información del Servidor",
		url:'modulos/administracion_sistema/programa/co/sql.consulta.php?nd='+new Date().getTime(),
		datatype: "json",
		colNames:['Id','Nombre','Pagina','Icono'],
		colModel:[
				{name:'id',index:'id', width:50},
				{name:'nombre',index:'nombre', width:200},
				{name:'pagina',index:'pagina', width:200},
				{name:'icono',index:'icono', width:200}
		],
		pager: jQuery('#pager_consulta_programa'),
		rowNum:20,
		imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
		sortname: 'nombre',
		viewrecords: true,
		sortorder: "asc"
	});
</script>

<div id="container-1">
	<ul>
		<li><a href="#fragment-1"><span>Informacion Principal</span></a></li>
		<li><a href="#fragment-2"><span>Tabs para carga</span></a></li>
		<li><a href="#fragment-3"><span>Tabs de Listado</span></a></li>
	</ul>
	<div id="fragment-1">
		Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
		Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
		Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
	</div>
	<div id="fragment-2">
		<form method="post" id="form_db_programa" name="form_db_programa">
		<table class="cuerpo_formulario">
			<tr>
				<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Programa </th>
			</tr>
			<tr>
				<th>Nombre:</th>
				<td >
					<input type="text"  size="40" name="programa_db_nombre" id="programa_db_nombre" value=""  />
				</td>
			</tr>
			<tr>
				<th>P&aacute;gina:</th>
				<td >
					<input name="programa_db_pagina" type="text" id="programa_db_pagina"   value="" size="40" />		
				</td>
			</tr>
			<tr>
				<td colspan="2" class="bottom_frame">&nbsp;</td>
			</tr>			
		  </table>
		</form>
	</div>
	<div id="fragment-3">
		<table id="list_consulta_programa" class="scroll" cellpadding="0" cellspacing="0" ></table> 
		<div id="pager_consulta_programa" class="scroll" style="text-align:center;"></div> 
	</div>
</div>
