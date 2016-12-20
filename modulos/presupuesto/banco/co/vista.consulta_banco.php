<script src="../../../administracion_sistema/banco/co/utilidades/jqgrid_demo/js/jquery.jqGrid.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#c_banco").jqGrid
	({
		height:280,
		recordtext:"Registro(s)",
		loadtext: "Recuperando Información del Servidor",		
		url:'modulos/administracion_sistema/banco/co/sql.consulta_banco.php?nd='+new Date().getTime(),
		datatype: "json",
		colNames:['ID','Banco','Sucursal', 'Tel&eacute;fono'],
		colModel:[
			{name:'id',index:'id', width:40},
			{name:'nombre',index:'nombre', width:150},
			{name:'sucursal',index:'sucursal', width:200},
			{name:'telefono',index:'telefono', width:150}
		],
		pager: $('#pager3'),
		rowNum:20,
		rowList:[20,50,100],
		imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/images',
		sortname: 'id_banco',
		viewrecords: true,
		sortorder: "asc"
	});
});
</script>
<div id="botonera">
	<img id="btn_eliminar" src="../../../administracion_sistema/banco/co/imagenes/null.gif" />
</div>
<form method="post" id="cbanco" name="cbanco">
<table style="width:590;" class="cuerpo_formulario">

	<tr>
		<th width="590px" class="titulo_frame" colspan="3"><img src="../../../administracion_sistema/banco/co/imagenes/iconos/kappfinder28x28.png" style="padding-right:5px;" align="absmiddle" />Consulta Perfil Modulo</th>
	</tr>
	<tr>
		<td>Buscar&nbsp;<input type="text" name="buscar" id="buscar" /></td>
	</tr>
	<tr>	
		<th>
			<!-- la tabla donde se creara el grid con clase 'scroll' -->
			<table id="c_banco" class="scroll" cellpadding="0" cellspacing="0"></table>
			<!-- el div donde radicaran los botones de control del grid -->
			<div id="pager3" class="scroll" style="text-align:center;"></div>
		</th>
	</tr>
</table>
</form>